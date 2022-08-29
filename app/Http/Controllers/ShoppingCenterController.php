<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCreateShopRequest;
use App\Http\Requests\GetGeoToAddressRequest;
use App\Http\Requests\GetNearestShopRequest;
use App\Http\Requests\GetUpdateShopRequest;
use App\Models\ShoppingCenter;
use ErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ShoppingCenterController extends Controller
{
    public function nearestShop(GetNearestShopRequest $request): JsonResponse
    {
        $long = $request->input('longitude');
        $lat = $request->input('latitude');
        $userLocation = [(float)$long, (float)$lat];

        $response = Http::withHeaders([
            'Api-Key' => 'service.da5008c9cbda4994a036ae42eca4fb4f'
        ])->get("https://api.neshan.org/v5/reverse", [
            'lng' => $long,
            'lat' => $lat,
        ]);

        $userAddress = $this->neshanCheck($response);

        $shoppingCenters = ShoppingCenter::all();
        $sourceIndex = $shoppingCenters->count();
        $destinationsIndex = $shoppingCenters->keys()->implode(';');

        $shopCoordinates = $shoppingCenters->pluck("location");

        $points = $shopCoordinates->merge([$userLocation])->map(function ($item) {
            return implode(',', $item);
        })->implode(';');

        $shopAddresses = $shopCoordinates->map(function ($item) {
            $response = Http::withHeaders([
                'Api-Key' => 'service.da5008c9cbda4994a036ae42eca4fb4f'
            ])->get("https://api.neshan.org/v5/reverse", [
                'lng' => $item[0],
                'lat' => $item[1],
            ]);
            return $this->neshanCheck($response);
        });

        $response = Http::get("http://router.project-osrm.org/table/v1/driving/$points", [
            'sources' => $sourceIndex,
            'destinations' => $destinationsIndex,
            'annotations' => 'distance'
        ]);
        $table = $response->json();

        $minIndex = array_keys($table["distances"][0], min($table["distances"][0]));

        $nearestShopLong = $table["destinations"][$minIndex[0]]["location"][0];
        $nearestShopLat = $table["destinations"][$minIndex[0]]["location"][1];

        $response = Http::get("http://router.project-osrm.org/route/v1/driving/$long,$lat;$nearestShopLong,$nearestShopLat", [
            'steps' => 'true',
            'geometries' => 'geojson',
            'annotations' => 'true',
        ]);
        $route = $response->json();
        $routeCoordinates = $route["routes"][0]["geometry"]["coordinates"];

        $featureCollection = $this->getFeatureCollection($shopCoordinates, $shopAddresses, $routeCoordinates, $userLocation, $userAddress);

        return $this->response(200, null, $featureCollection);
    }

    private function neshanCheck($response)
    {
        if ($response->failed()) {
            try {
                $message = $response->json("message");
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
            Log::info($message);
            throw new ErrorException(Lang::get('messages/errors.service_not_available'), 503);
        }

        return $response->json("formatted_address");
    }

    private function getFeatureCollection($shopCoordinates, $shopAddresses, $routeCoordinates, $userLocation, $userAddress): array
    {
        $features = [];
        foreach ($shopCoordinates as $i => $points) {
            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'marker-color' => '#fd3535',
                    'marker-size' => 'medium',
                    'marker-symbol' => 'clothing-store'
                ],
                'geometry' => ['type' => 'Point', 'coordinates' => $points],
                'address' => $shopAddresses[$i]
            ];
        }
        $features[] = [
            'type' => 'Feature',
            'properties' => [
                'marker-color' => '#1def01',
                'marker-size' => 'medium',
                'marker-symbol' => 'circle'
            ],
            'geometry' => ['type' => 'Point', 'coordinates' => $userLocation],
            'address' => $userAddress
        ];
        $features[] = [
            'type' => 'Feature',
            'properties' => [],
            'geometry' => ['type' => 'LineString', 'coordinates' => $routeCoordinates]
        ];
        $featurecollection = array(
            'type' => 'FeatureCollection',
            'features' => $features
        );
        return $featurecollection;
    }

    public function shopAddresses(): JsonResponse
    {
        $shopLocations = ShoppingCenter::all()->pluck('location');
        $shopAddresses = $shopLocations->map(function ($item) {
            $response = Http::withHeaders([
                'Api-Key' => 'service.da5008c9cbda4994a036ae42eca4fb4f'
            ])->get("https://api.neshan.org/v5/reverse", [
                'lng' => $item[0],
                'lat' => $item[1],
            ]);
            return $response->json();
        });

        return $this->response(200,null,$shopAddresses);
    }

    public function addressToGeo(Request $request):JsonResponse
    {
        $address = $request->input('address');

        $response = Http::withHeaders([
            'Api-Key' => 'service.da5008c9cbda4994a036ae42eca4fb4f'
        ])->get("https://api.neshan.org/v4/geocoding", [
            'address' => $address
        ]);
        $geoCode = $response->json();

        return $this->response(200,null,$geoCode);

    }

    public function geoToAddress(GetGeoToAddressRequest $request): JsonResponse
    {
        $long = $request->input('longitude');
        $lat = $request->input('latitude');

        $response = Http::withHeaders([
            'Api-Key' => 'service.da5008c9cbda4994a036ae42eca4fb4f'
        ])->get("https://api.neshan.org/v5/reverse", [
            'lat' => $lat,
            'lng' => $long,
        ]);
        $reverse = $response->json();

        return $this->response(200, null, $reverse);

    }

    public function store(GetCreateShopRequest $request): JsonResponse
    {
        $user = $request->user();
        $name = $request->input('name');
        $long = $request->input('longitude');
        $lat = $request->input('latitude');
        $shop = $user->shopping_centers()->create([
            'name' => $name,
            'location' => [$long,$lat]
        ]);

        return $this->response(200,Lang::get('messages/crud.create_shop'),$shop->name);
    }

    public function update(GetUpdateShopRequest $request,ShoppingCenter $shoppingCenter): JsonResponse
    {
        $name = $request->input('name');
        $long = $request->input('longitude');
        $lat = $request->input('latitude');

        $shoppingCenter->update([
            'name' => $name,
            'location' => [$long,$lat]
        ]);

        return $this->response(200,Lang::get('messages/crud.update_shop'),$shoppingCenter->name);
    }

    public function index():JsonResponse
    {
        return $this->response(200,null,ShoppingCenter::all());
    }
}
