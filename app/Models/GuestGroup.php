<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuestGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'notes',
    ];

    public function weddingGuests(): HasMany
    {
        return $this->hasMany(WeddingGuest::class);
    }
}
