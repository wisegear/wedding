<?php

use App\Models\User;
use App\Models\WeddingPartyMember;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('admins can create multiple people in every role', function (string $role) {
    $this->actingAs(User::factory()->admin()->create());
    foreach (['Alex', 'Sam'] as $name) {
        $this->post(route('admin.wedding-party.store'), ['name' => $name, 'role' => $role, 'parent_side' => $role === 'Parent' ? 'Bride' : null, 'description' => 'Our lovely friend'])
            ->assertRedirect(route('admin.wedding-party.index'));
    }
    $this->assertDatabaseCount('wedding_party_members', 2);
    $this->get(route('admin.wedding-party.index'))->assertSuccessful()->assertSee('Alex')->assertSee('Sam');
    $this->get(route('wedding-party'))->assertSuccessful()->assertSeeText($role)->assertSeeText('Our lovely friend');
})->with(WeddingPartyMember::ROLES);

test('photos become square thumbnails and replacements and deletion clean up files', function () {
    $this->actingAs(User::factory()->admin()->create());
    $data = ['name' => 'Alex', 'role' => 'Best Man', 'description' => 'A friend'];
    $this->post(route('admin.wedding-party.store'), $data + ['photo' => UploadedFile::fake()->image('portrait.png', 600, 400)])
        ->assertSessionHasNoErrors()->assertRedirect();
    $member = WeddingPartyMember::query()->sole();
    $oldPath = $member->photo_path;
    $size = getimagesize(Storage::disk('public')->path($oldPath));
    expect([$size[0], $size[1]])->toBe([200, 200]);
    $this->get(route('admin.wedding-party.index', ['edit' => $member->id]))->assertSuccessful()->assertSee('Current photo of Alex');
    $this->patch(route('admin.wedding-party.update', $member), $data)->assertRedirect();
    expect($member->refresh()->photo_path)->toBe($oldPath);
    $this->patch(route('admin.wedding-party.update', $member), array_replace($data, ['name' => 'Sam', 'photo' => UploadedFile::fake()->image('new.jpg', 100, 300)]))->assertRedirect();
    $newPath = $member->refresh()->photo_path;
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($newPath);
    $size = getimagesize(Storage::disk('public')->path($newPath));
    expect([$size[0], $size[1]])->toBe([200, 200]);
    $this->get(route('admin.wedding-party.confirm-delete', $member))->assertSuccessful()->assertSee('This cannot be undone.');
    $this->assertModelExists($member);
    $this->delete(route('admin.wedding-party.destroy', $member))->assertRedirect();
    $this->assertModelMissing($member);
    Storage::disk('public')->assertMissing($newPath);
});

test('an existing photo can be removed without deleting the person', function () {
    $member = WeddingPartyMember::factory()->create(['photo_path' => 'wedding-party/old.jpg']);
    Storage::disk('public')->put($member->photo_path, 'old');
    $this->actingAs(User::factory()->admin()->create())->patch(route('admin.wedding-party.update', $member), [
        'name' => $member->name, 'role' => 'Parent', 'parent_side' => 'Bride', 'remove_photo' => '1',
    ])->assertRedirect();
    expect($member->refresh()->photo_path)->toBeNull();
    Storage::disk('public')->assertMissing('wedding-party/old.jpg');
});

test('invalid inputs do not change records or photos', function () {
    $member = WeddingPartyMember::factory()->create(['photo_path' => 'wedding-party/old.jpg']);
    Storage::disk('public')->put($member->photo_path, 'old');
    $this->actingAs(User::factory()->admin()->create())->patch(route('admin.wedding-party.update', $member), [
        'name' => '', 'role' => 'Unknown', 'description' => str_repeat('x', 5001), 'photo' => UploadedFile::fake()->create('bad.pdf', 10, 'application/pdf'),
    ])->assertSessionHasErrors(['name', 'role', 'description', 'photo']);
    Storage::disk('public')->assertExists($member->photo_path);
    $this->post(route('admin.wedding-party.store'), ['name' => 'Alex', 'role' => 'Best Man', 'photo' => UploadedFile::fake()->image('large.jpg')->size(10241)])
        ->assertSessionHasErrors('photo');
    $this->assertDatabaseCount('wedding_party_members', 1);
});

test('only admins can manage the wedding party', function () {
    $member = WeddingPartyMember::factory()->create();
    $urls = [
        ['get', route('admin.wedding-party.index')],
        ['post', route('admin.wedding-party.store')],
        ['patch', route('admin.wedding-party.update', $member)],
        ['get', route('admin.wedding-party.confirm-delete', $member)],
        ['delete', route('admin.wedding-party.destroy', $member)],
    ];
    foreach ($urls as [$method, $url]) {
        $this->$method($url)->assertRedirect(route('login'));
    }
    $this->actingAs(User::factory()->create());
    foreach ($urls as [$method, $url]) {
        $this->$method($url)->assertForbidden();
    }
    $this->assertModelExists($member);
});

test('parents require a bride or groom choice and display it publicly', function (string $side) {
    $this->actingAs(User::factory()->admin()->create());
    $data = ['name' => 'Robin', 'role' => 'Parent', 'parent_side' => $side];
    $this->post(route('admin.wedding-party.store'), $data)->assertRedirect();
    $member = WeddingPartyMember::query()->sole();
    $this->get(route('wedding-party'))->assertSeeText('Parent of the '.$side);
    $this->get(route('admin.wedding-party.index', ['edit' => $member->id]))->assertSuccessful()->assertSee('Parent of whom?');
    $this->patch(route('admin.wedding-party.update', $member), ['name' => 'Robin', 'role' => 'Parent'])->assertSessionHasErrors('parent_side');
    $this->patch(route('admin.wedding-party.update', $member), ['name' => 'Robin', 'role' => 'Parent', 'parent_side' => 'Unknown'])->assertSessionHasErrors('parent_side');
    expect($member->refresh()->parent_side)->toBe($side);
    $this->patch(route('admin.wedding-party.update', $member), ['name' => 'Robin', 'role' => 'Groom', 'parent_side' => $side])->assertRedirect();
    expect($member->refresh()->parent_side)->toBeNull();
})->with(['Bride', 'Groom']);

test('a new parent cannot be created without a side', function () {
    $this->actingAs(User::factory()->admin()->create())->post(route('admin.wedding-party.store'), ['name' => 'Robin', 'role' => 'Parent'])
        ->assertSessionHasErrors('parent_side');
    $this->assertDatabaseCount('wedding_party_members', 0);
});

test('admins can update the wedding party panel title and introduction', function () {
    $this->actingAs(User::factory()->admin()->create());
    $this->get(route('admin.wedding-party.index'))->assertSuccessful()->assertSee('Meet the wedding party');
    $this->patch(route('admin.wedding-party.page'), ['wedding_party_title' => 'Our favourite people', 'wedding_party_intro' => 'Meet our family and friends.'])
        ->assertRedirect(route('admin.wedding-party.index'));
    $this->get(route('wedding-party'))->assertSuccessful()->assertSeeText('Our favourite people')->assertSeeText('Meet our family and friends.');
    $this->patch(route('admin.wedding-party.page'), ['wedding_party_title' => '', 'wedding_party_intro' => str_repeat('x', 5001)])
        ->assertSessionHasErrors(['wedding_party_title', 'wedding_party_intro']);
    $this->get(route('wedding-party'))->assertSeeText('Our favourite people');
});

test('non admins cannot change wedding party panel text', function () {
    $this->patch(route('admin.wedding-party.page'), [])->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->patch(route('admin.wedding-party.page'), [])->assertForbidden();
});
