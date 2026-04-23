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
        $routeSegment = 'murmansk_anadyr';
        $targetOrderIndex = 3;

        DB::transaction(function () use ($routeSegment, $targetOrderIndex) {
            $existingBySlug = DB::table('route_stops')
                ->where('route_segment', $routeSegment)
                ->where('slug', 'kanin-cape')
                ->first();

            if ($existingBySlug) {
                if ((int) $existingBySlug->order_index !== $targetOrderIndex) {
                    $this->shiftStopsOrderIndexes($routeSegment, $targetOrderIndex, (int) $existingBySlug->id);
                }

                DB::table('route_stops')
                    ->where('id', $existingBySlug->id)
                    ->update([
                        'order_index' => $targetOrderIndex,
                        'name' => 'Канин мыс',
                        'icon_slug' => 'light-keeper',
                        'x_coord' => 716,
                        'y_coord' => 470,
                        'is_stop' => true,
                        'level_number' => 3,
                        'salary_days' => 1,
                        'updated_at' => now(),
                    ]);

                return;
            }

            $this->shiftStopsOrderIndexes($routeSegment, $targetOrderIndex);

            DB::table('route_stops')->insert([
                'route_segment' => $routeSegment,
                'slug' => 'kanin-cape',
                'order_index' => $targetOrderIndex,
                'name' => 'Канин мыс',
                'icon_slug' => 'light-keeper',
                'x_coord' => 716,
                'y_coord' => 470,
                'is_stop' => true,
                'level_number' => 3,
                'salary_days' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    private function shiftStopsOrderIndexes(string $routeSegment, int $fromOrderIndex, ?int $exceptId = null): void
    {
        $maxOrderIndex = DB::table('route_stops')
            ->where('route_segment', $routeSegment)
            ->where('order_index', '>=', $fromOrderIndex)
            ->when($exceptId !== null, fn ($query) => $query->where('id', '!=', $exceptId))
            ->max('order_index');

        if ($maxOrderIndex === null) {
            return;
        }

        for ($orderIndex = (int) $maxOrderIndex; $orderIndex >= $fromOrderIndex; $orderIndex--) {
            $stopAtIndex = DB::table('route_stops')
                ->where('route_segment', $routeSegment)
                ->where('order_index', $orderIndex)
                ->when($exceptId !== null, fn ($query) => $query->where('id', '!=', $exceptId))
                ->first(['id']);

            if (!$stopAtIndex) {
                continue;
            }

            DB::table('route_stops')
                ->where('id', $stopAtIndex->id)
                ->update([
                    'order_index' => $orderIndex + 1,
                    'updated_at' => now(),
                ]);
        }
    }
}
