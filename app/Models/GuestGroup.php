<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuestGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'invitation_code',
        'notes',
    ];

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'guest_group_user')
            ->withTimestamps();
    }
}
