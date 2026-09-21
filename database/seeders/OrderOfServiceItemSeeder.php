<?php

namespace Database\Seeders;

use App\Models\OrderOfServiceItem;
use Illuminate\Database\Seeder;

class OrderOfServiceItemSeeder extends Seeder
{
    public function run(): void
    {
        if (OrderOfServiceItem::query()->exists()) {
            return;
        }

        foreach (['14:00' => 'Get smashed', '14:15' => 'Ceremony', '14:30' => 'Get married', '14:45' => 'Get more smashed'] as $time => $title) {
            OrderOfServiceItem::query()->create(['title' => $title, 'starts_at' => $time]);
        }
    }
}
