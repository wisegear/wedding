<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_group_id',
        'first_name',
        'last_name',
        'display_name',
        'email',
        'phone',
        'rsvp_status',
        'dinner_choice',
        'dietary_requirements',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'dinner_choice' => 'array',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(GuestGroup::class, 'guest_group_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'guest_group_user')
            ->withTimestamps();
    }

    public function registrationLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GuestGroupUser::class);
    }
}
