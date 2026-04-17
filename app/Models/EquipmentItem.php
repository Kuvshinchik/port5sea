<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentItem extends Model
{
    protected $fillable = [
        'code',
        'name',
        'icon',
        'category',
        'price',
        'weight',
        'effect',
        'food_days',
        'special_effects',
        'is_active',
    ];

    protected $casts = [
        'special_effects' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Получить все предметы по категории
     */
    public static function getByCategory(string $category): array
    {
        return self::where('category', $category)
            ->where('is_active', true)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->code,
                    'name' => $item->name,
                    'icon' => $item->icon,
                    'price' => $item->price,
                    'weight' => $item->weight,
                    'effect' => $item->effect,
                    'days' => $item->food_days,
                    'category' => $item->category,
                ];
            })
            ->toArray();
    }

    /**
     * Получить всё снаряжение для игры
     */
    public static function getAllForGame(): array
    {
        return [
            'equipment' => self::getByCategory('equipment'),
            'food' => self::getByCategory('food'),
            'medicine' => self::getByCategory('medicine'),
        ];
    }
}
