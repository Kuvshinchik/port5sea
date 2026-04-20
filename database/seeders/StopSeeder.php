<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('route_stops')->updateOrInsert(
            [
                'route_segment' => 'murmansk_anadyr',
                'slug' => 'kanin-cape',
            ],
            [
                'order_index' => 3,
                'name' => 'Канин мыс',
                'icon_slug' => 'light-keeper',
                'x_coord' => 716,
                'y_coord' => 470,
                'is_stop' => true,
                'level_number' => 3,
                'salary_days' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
