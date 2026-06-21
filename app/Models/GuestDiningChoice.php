<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestDiningChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_guest_id',
        'dining_option_id',
        'notes',
    ];

    public function weddingGuest(): BelongsTo
    {
        return $this->belongsTo(WeddingGuest::class);
    }

    public function diningOption(): BelongsTo
    {
        return $this->belongsTo(DiningOption::class);
    }
}
