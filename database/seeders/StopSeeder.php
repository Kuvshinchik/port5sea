<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
        /** @var Collection<int, object> $stopsToShift */
        $stopsToShift = DB::table('route_stops')
            ->where('route_segment', $routeSegment)
            ->where('order_index', '>=', $fromOrderIndex)
            ->when($exceptId !== null, fn ($query) => $query->where('id', '!=', $exceptId))
            ->orderByDesc('order_index')
            ->get(['id', 'order_index']);

        foreach ($stopsToShift as $stop) {
            DB::table('route_stops')
                ->where('id', $stop->id)
                ->update([
                    'order_index' => $stop->order_index + 1,
                    'updated_at' => now(),
                ]);
        }
    }
}
