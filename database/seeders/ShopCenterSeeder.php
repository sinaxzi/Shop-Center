<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\ShoppingCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $shops = [
            [
                'name' => 'MeghdadIT',
                'location' => [
                    51.3411713,
                    35.7479055
                ]
            ],
            [
                'name' => 'Shahrvand',
                'location' => [
                    51.4932632,
                    35.7431685
                ]
            ],
            [
                'name' => 'HyperStar',
                'location' => [
                    51.4798737,
                    35.6681748
                ]
            ],
            [
                'name' => 'Iranmall',
                'location' => [
                    51.3525009,
                    35.6807251
                ]
            ]
        ];

        foreach ($shops as $shop) {
            Manager::find(rand(1,50))->shopping_centers()->create([
                "name" => $shop['name'],
                "location" => $shop['location']
            ]);
        }
    }
}
