<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    protected $fillable = [
        'sub_category_id',
        'state_id',
        'city_id',
        'title',
        'slug',
        'description',
        'price',
        'seller_name',
        'seller_phone',
        'whatsapp_phone',
        'status',
        'is_featured',
        'published_at',
        'avg_rating',
        'reviews_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'avg_rating' => 'decimal:2',
            'reviews_count' => 'integer',
        ];
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(LocationState::class, 'state_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(LocationCity::class, 'city_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function savedByUsers(): HasMany
    {
        return $this->hasMany(SavedListing::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
