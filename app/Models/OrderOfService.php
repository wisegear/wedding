<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOfService extends Model
{
    protected $fillable = ['id', 'heading', 'tagline', 'partner_one', 'partner_two', 'intro', 'guest_intro', 'wedding_party_title', 'wedding_party_intro'];

    protected $attributes = ['heading' => 'Order of service'];

    public static function settings(): self
    {
        $settings = static::query()->find(1) ?? new static;

        $settings->partner_one ??= config('wedding.couple.partner_one');
        $settings->partner_two ??= config('wedding.couple.partner_two');
        $settings->intro ??= config('wedding.intro');
        $settings->guest_intro ??= config('wedding.guest_intro');

        $settings->wedding_party_title ??= config('wedding.wedding_party_title');
        $settings->wedding_party_intro ??= config('wedding.wedding_party_intro');

        return $settings;
    }
}
