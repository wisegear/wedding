<?php

namespace App\Models;

use Database\Factories\WeddingPartyMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingPartyMember extends Model
{
    /** @use HasFactory<WeddingPartyMemberFactory> */
    use HasFactory;

    public const ROLES = ['Bride', 'Groom', 'Maid of Honour', 'Bridesmaids', 'Best Man', 'Groomsmen', 'Parent', 'Flower Girl', 'Page Boy'];

    protected $fillable = ['name', 'role', 'description', 'photo_path', 'parent_side'];

    public function roleLabel(): string
    {
        return $this->role === 'Parent' && $this->parent_side
            ? 'Parent of the '.$this->parent_side
            : $this->role;
    }
}
