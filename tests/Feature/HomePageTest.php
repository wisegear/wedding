<?php

use Database\Seeders\OrderOfServiceItemSeeder;

test('home page shows the order of service instead of the old information panels', function () {
    $this->seed(OrderOfServiceItemSeeder::class);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSeeTextInOrder([
            'Order of service',
            '2pm',
            'Get smashed',
            '2.15pm',
            'Ceremony',
            '2.30pm',
            'Get married',
            '2.45pm',
            'Get more smashed',
            'Share QR Code',
        ])
        ->assertDontSeeText('The Day')
        ->assertDontSeeText('Guest Info')
        ->assertDontSeeText('Historic, warm, and full of character.');
});
