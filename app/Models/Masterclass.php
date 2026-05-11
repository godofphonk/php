<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Masterclass extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'description',
        'date',
        'time',
        'max_participants',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function getAvailableSpotsAttribute(): int
    {
        return $this->max_participants - $this->registrations()->count();
    }

    public function hasAvailableSpots(): bool
    {
        return $this->available_spots > 0;
    }
}
