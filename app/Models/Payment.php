<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'reference',
        'currency',
        'amount_kobo',
        'status',
        'access_code',
        'authorization_url',
        'verified_at',
        'provider_payload',
    ];

    protected function casts(): array
    {
        return [
            'amount_kobo' => 'integer',
            'verified_at' => 'datetime',
            'provider_payload' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
