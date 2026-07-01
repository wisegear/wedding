<?php

test('wedding party page can be viewed', function () {
    $response = $this->get(route('wedding-party'));

    $response
        ->assertOk()
        ->assertSee('Meet the wedding party')
        ->assertSee('Bridesmaids')
        ->assertSee('Best Man')
        ->assertSee('Groomsmen')
        ->assertSee('Flower Girls &amp; Page Boys', false);
});

test('wedding party page is linked from public navigation', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee(route('wedding-party'))
        ->assertSee('Wedding Party');
});
