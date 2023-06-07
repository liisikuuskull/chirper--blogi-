<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chirp extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
                'message',
            ];
    use HasFactory;

    public function comments(): HasMany
    {
    return $this->hasMany(Comment::class);
    }
}
