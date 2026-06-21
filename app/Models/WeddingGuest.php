<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_group_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'is_primary_contact',
        'rsvp_status',
        'dietary_notes',
    ];

    protected function casts(): array
    {
        return [
            'is_primary_contact' => 'boolean',
        ];
    }

    public function guestGroup(): BelongsTo
    {
        return $this->belongsTo(GuestGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function diningChoices(): HasMany
    {
        return $this->hasMany(GuestDiningChoice::class);
    }
}
