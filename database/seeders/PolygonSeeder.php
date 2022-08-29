<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolygonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coordinates = [
            [51.3703537, 35.7646221],
            [51.3593674, 35.6564594],
            [51.4170456, 35.6450212],
            [51.3703537, 35.7646221],
            [51.4760971, 35.7467909],
            [51.3703537, 35.7646221],
        ];

        Location::create([
            "polygon" => $coordinates
        ]);
    }
}
