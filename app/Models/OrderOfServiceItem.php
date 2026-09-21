<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\OrderOfServiceItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOfServiceItem extends Model
{
    /** @use HasFactory<OrderOfServiceItemFactory> */
    use HasFactory;

    protected $fillable = ['title', 'starts_at'];

    public function displayTime(): string
    {
        $time = CarbonImmutable::parse($this->starts_at);

        return $time->format($time->minute === 0 ? 'ga' : 'g.ia');
    }

    public function formTime(): string
    {
        return CarbonImmutable::parse($this->starts_at)->format('H:i');
    }
}
