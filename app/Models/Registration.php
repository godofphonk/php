<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'masterclass_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function masterclass(): BelongsTo
    {
        return $this->belongsTo(Masterclass::class);
    }
}
