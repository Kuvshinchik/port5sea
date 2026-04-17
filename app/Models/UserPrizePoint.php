<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPrizePoint extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance', 'last_spin_at'];

    protected $dates = ['last_spin_at']; // ← вот эта строка решает проблему

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}