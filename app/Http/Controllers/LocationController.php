<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRouteInPolygonRequest;
use App\Http\Requests\GetRouteRequest;
use App\Http\Requests\GetNearestShopRequest;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Location\Coordinate;
use Location\Polygon;

class LocationController extends Controller
{
    public function routing(GetRouteRequest $request): JsonResponse
    {
        $point1Long = $request->input('longitude');
        $point1Lat = $request->input('latitude');
        $point2Lat = $request->input('longitude2');
        $point2Long = $request->input('latitude2');

        $response = Http::get("http://router.project-osrm.org/route/v1/driving/$point1Long,$point1Lat;$point2Long,$point2Lat",[
            'steps' => 'true',
            'geometries' => 'geojson',
            'annotations' => 'true',
            'overview' => 'full'
        ]);
        $route = $response->json('routes.0.geometry.coordinates');

        return $this->response(200, null, $route);
    }

    public function routeInPolygon(GetRouteInPolygonRequest $request): JsonResponse
    {
        $point1Long = $request->input('longitude');
        $point1Lat = $request->input('latitude');
        $point2Lat = $request->input('longitude2');
        $point2Long = $request->input('latitude2');

        $response = Http::get("http://router.project-osrm.org/route/v1/driving/$point1Long,$point1Lat;$point2Long,$point2Lat",[
            'steps' => 'true',
            'geometries' => 'geojson',
            'annotations' => 'true',
            'overview' => 'full'
        ]);
        $route = $response->json();
        $location = Location::find(1);
        $geofence = new Polygon();

        foreach ($location->polygon as $polygonPoint) {
            $geofence->addPoint(new Coordinate($polygonPoint['longitude'], $polygonPoint['latitude']));
        }

        foreach ($route['routes'][0]['geometry']['coordinates'] as $i => $step) {
            if (!$geofence->contains(new Coordinate( $step[0], $step[1]))) {
                unset($route['routes'][0]['geometry']['coordinates'][$i]);
            }
        }

        $route['routes'][0]['geometry']['coordinates'] = array_values($route['routes'][0]['geometry']['coordinates']);

        return $this->response(200, null, $route['routes'][0]['geometry']);
    }

    public function matchingPoints(Request $request): JsonResponse
    {
        $myLine = $request->input('line');

        $response = Http::get("http://router.project-osrm.org/match/v1/driving/$myLine",[
            'steps'=>'true',
            'overview' =>'full',
            'geometries'=>'geojson'
        ]);
        $match = $response->json();

        return $this->response(200, null, $match);
    }
}
