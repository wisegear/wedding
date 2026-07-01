<?php

test('q and a page can be viewed', function () {
    $response = $this->get(route('q-and-a'));

    $response
        ->assertOk()
        ->assertSee('Wedding questions')
        ->assertSee('When should I RSVP by?')
        ->assertSee('Can I bring a plus one?')
        ->assertSee('Can guests upload photos?');
});

test('q and a page is linked from public navigation', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Open navigation')
        ->assertSee(route('q-and-a'))
        ->assertSee('Q & A', false);
});
