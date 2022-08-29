<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\DB;

class PolygonCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $coordinates = unpack('VSRID/corder/ltype/Lnum_rings/Lnum_points/d*', $value);
        unset($coordinates['SRID'], $coordinates['type'], $coordinates['num_rings'], $coordinates['order'], $coordinates['num_points']);

        $points = array_map(function (array $arr) {
            return [
                'longitude' => $arr[0],
                'latitude' => $arr[1],
            ];
        }, array_chunk($coordinates, 2));

        return $points;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        $coordinatesString = collect($value)->map(function ($coordinate) {
            return "$coordinate[0] $coordinate[1]";
        })->implode(',');

        return DB::raw("(ST_GeomFromText('POLYGON(($coordinatesString))'))");
    }
}
