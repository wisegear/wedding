<?php

use App\Models\WeddingPartyMember;

test('wedding party page can be viewed', function () {
    $response = $this->get(route('wedding-party'));

    $response
        ->assertOk()
        ->assertSee('Meet the wedding party')
        ->assertSee('We will introduce our wedding party here soon.');
});

test('wedding party page is linked from public navigation', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee(route('wedding-party'))
        ->assertSee('Wedding Party');
});

test('wedding party photos use the current site rather than the configured app host', function () {
    config(['app.url' => 'https://different-site.test']);
    WeddingPartyMember::factory()->create([
        'name' => 'Portrait example',
        'role' => 'Bride',
        'photo_path' => 'wedding-party/portrait.jpg',
    ]);

    $this->get(route('wedding-party'))
        ->assertSuccessful()
        ->assertSee('src="/storage/wedding-party/portrait.jpg"', false)
        ->assertDontSee('https://different-site.test/storage/wedding-party/portrait.jpg');
});

test('the couple appear first followed by parents then the rest of the party', function () {
    foreach (['Maid of Honour', 'Parent', 'Groom', 'Bride', 'Best Man'] as $role) {
        WeddingPartyMember::factory()->create(['name' => $role.' person', 'role' => $role]);
    }

    $this->get(route('wedding-party'))->assertSuccessful()
        ->assertSeeTextInOrder(['Bride person', 'Groom person', 'Parent person', 'Maid of Honour person', 'Best Man person']);
});

test('parents and attendants align with the correct side of the couple', function () {
    $brideParent = WeddingPartyMember::factory()->create(['role' => 'Parent', 'parent_side' => 'Bride']);
    $groomParent = WeddingPartyMember::factory()->create(['role' => 'Parent', 'parent_side' => 'Groom']);
    $maid = WeddingPartyMember::factory()->create(['role' => 'Maid of Honour']);
    $bestMan = WeddingPartyMember::factory()->create(['role' => 'Best Man']);

    $this->get(route('wedding-party'))->assertSuccessful()->assertViewHas('groups', function (array $groups) use ($brideParent, $groomParent, $maid, $bestMan): bool {
        expect($groups[2]['members']->values()->modelKeys())->toBe([$brideParent->id]);
        expect($groups[3]['members']->values()->modelKeys())->toBe([$groomParent->id]);
        expect($groups[4]['members']->values()->modelKeys())->toBe([$maid->id]);
        expect($groups[5]['members']->values()->modelKeys())->toBe([$bestMan->id]);
        expect($groups[2]['position'])->toBe('lg:col-start-1 lg:row-start-2');
        expect($groups[3]['position'])->toBe('lg:col-start-2 lg:row-start-2');
        expect($groups[4]['position'])->toBe('lg:col-start-1 lg:row-start-3');
        expect($groups[5]['position'])->toBe('lg:col-start-2 lg:row-start-3');

        return true;
    });
});
