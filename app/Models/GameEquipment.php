<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameEquipment extends Model
{
    protected $table = 'game_equipment';

    protected $fillable = [
        'game_progress_id',
        'equipment_item_id',
        'quantity',
        'purchase_price',
    ];

    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }

    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    /**
     * Получить данные для JS
     */
    public function toEquipmentData(): array
    {
        $item = $this->equipmentItem;

        return [
            'id' => $item->code,
            'name' => $item->name,
            'icon' => $item->icon,
            'category' => $item->category,
            'price' => $item->price,
            'weight' => $item->weight,
            'effect' => $item->effect,
            'days' => $item->food_days,
            'quantity' => $this->quantity,
            'purchased' => true,
        ];
    }
}
