<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserToy extends Model
{
    protected $fillable = [
        'user_id',
        'toy_character_id',
        'qr_code_id',
        'bonus_money',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toyCharacter(): BelongsTo
    {
        return $this->belongsTo(ToyCharacter::class);
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(ToyQrCode::class, 'qr_code_id');
    }
}
