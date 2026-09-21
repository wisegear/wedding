<?php

use App\Models\OrderOfServiceItem;
use App\Models\User;
use Database\Seeders\OrderOfServiceItemSeeder;

test('admins can add and edit schedule items and the home page reflects changes', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)->get(route('admin.order-of-service.index'))->assertSuccessful()->assertSee('Add item');
    $this->post(route('admin.order-of-service.store'), ['title' => 'Evening reception', 'starts_at' => '19:00'])
        ->assertRedirect(route('admin.order-of-service.index'));
    $item = OrderOfServiceItem::query()->sole();
    $this->get(route('admin.order-of-service.index', ['edit' => $item->id]))
        ->assertSuccessful()->assertSee('Evening reception')->assertSee('19:00');
    $this->patch(route('admin.order-of-service.update', $item), ['title' => 'Wedding breakfast', 'starts_at' => '16:30'])
        ->assertRedirect(route('admin.order-of-service.index'));
    expect($item->refresh()->title)->toBe('Wedding breakfast');
    OrderOfServiceItem::factory()->create(['title' => 'Arrival', 'starts_at' => '13:00']);
    $this->get(route('home'))->assertSuccessful()
        ->assertSeeTextInOrder(['1pm', 'Arrival', '4.30pm', 'Wedding breakfast'])
        ->assertDontSeeText('Evening reception');
});

test('non admins cannot view or change the schedule', function () {
    $item = OrderOfServiceItem::factory()->create();
    $this->get(route('admin.order-of-service.index'))->assertRedirect(route('login'));
    $this->post(route('admin.order-of-service.store'), [])->assertRedirect(route('login'));
    $this->patch(route('admin.order-of-service.update', $item), [])->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create());
    $this->get(route('admin.order-of-service.index'))->assertForbidden();
    $this->post(route('admin.order-of-service.store'), ['title' => 'Test', 'starts_at' => '12:00'])->assertForbidden();
    $this->patch(route('admin.order-of-service.update', $item), ['title' => 'Test', 'starts_at' => '12:00'])->assertForbidden();
});

test('schedule changes require a valid title and time', function (array $data, string $field) {
    $this->actingAs(User::factory()->admin()->create());
    $item = OrderOfServiceItem::factory()->create(['title' => 'Ceremony', 'starts_at' => '14:00']);
    $this->post(route('admin.order-of-service.store'), $data)->assertSessionHasErrors($field);
    $this->patch(route('admin.order-of-service.update', $item), $data)->assertSessionHasErrors($field);
    expect($item->refresh()->title)->toBe('Ceremony');
    $this->assertDatabaseCount('order_of_service_items', 1);
})->with([
    [['title' => '', 'starts_at' => '14:00'], 'title'],
    [['title' => str_repeat('x', 256), 'starts_at' => '14:00'], 'title'],
    [['title' => 'Ceremony', 'starts_at' => '25:00'], 'starts_at'],
    [['title' => 'Ceremony', 'starts_at' => ''], 'starts_at'],
]);

test('empty schedules have useful messages and missing edit items return not found', function () {
    $this->get(route('home'))->assertSuccessful()->assertSeeText('Timings will be shared soon.');
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.order-of-service.index'))->assertSuccessful()->assertSeeText('No items yet.');
    $this->get(route('admin.order-of-service.index', ['edit' => 999]))->assertNotFound();
});

test('seeding preserves an existing edited schedule', function () {
    $this->seed(OrderOfServiceItemSeeder::class);
    OrderOfServiceItem::query()->firstOrFail()->update(['title' => 'Welcome']);
    $this->seed(OrderOfServiceItemSeeder::class);
    $this->assertDatabaseCount('order_of_service_items', 4);
    $this->assertDatabaseHas('order_of_service_items', ['title' => 'Welcome']);
});

test('admins see a warning before deleting an item', function () {
    $item = OrderOfServiceItem::factory()->create(['title' => 'First dance']);
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.order-of-service.confirm-delete', $item))
        ->assertSuccessful()->assertSeeText('First dance')
        ->assertSeeText('This cannot be undone.')->assertSeeText('Cancel');
    $this->assertModelExists($item);
    $this->delete(route('admin.order-of-service.destroy', $item))
        ->assertRedirect(route('admin.order-of-service.index'));
    $this->assertModelMissing($item);
    $this->get(route('home'))->assertDontSeeText('First dance');
});

test('admins can save and clear the panel tagline without changing items', function () {
    $item = OrderOfServiceItem::factory()->create();
    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.order-of-service.settings'), ['heading' => 'Our wedding day', 'tagline' => 'Celebrate with us'])
        ->assertRedirect(route('admin.order-of-service.index'));
    $this->get(route('home'))->assertSeeTextInOrder(['Our wedding day', 'Celebrate with us', $item->title]);
    $this->get(route('admin.order-of-service.index'))->assertSee('Our wedding day')->assertSee('Celebrate with us');
    $this->patch(route('admin.order-of-service.settings'), ['heading' => 'The celebrations', 'tagline' => ''])
        ->assertSessionHasNoErrors();
    $this->get(route('home'))->assertSeeText('The celebrations')->assertDontSeeText('Celebrate with us');
    $this->assertDatabaseCount('order_of_services', 1);
    $this->assertModelExists($item);
});

test('panel settings are validated', function (array $data, string $field) {
    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.order-of-service.settings'), $data)->assertSessionHasErrors($field);
    $this->assertDatabaseCount('order_of_services', 0);
})->with([
    [['heading' => '', 'tagline' => null], 'heading'],
    [['heading' => str_repeat('a', 256)], 'heading'],
    [['heading' => 'Our day', 'tagline' => str_repeat('a', 1001)], 'tagline'],
]);

test('guests cannot delete items or change the panel settings', function () {
    $item = OrderOfServiceItem::factory()->create();
    $this->get(route('admin.order-of-service.confirm-delete', $item))->assertRedirect(route('login'));
    $this->delete(route('admin.order-of-service.destroy', $item))->assertRedirect(route('login'));
    $this->patch(route('admin.order-of-service.settings'), ['heading' => 'Changed'])->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create());
    $this->get(route('admin.order-of-service.confirm-delete', $item))->assertForbidden();
    $this->delete(route('admin.order-of-service.destroy', $item))->assertForbidden();
    $this->patch(route('admin.order-of-service.settings'), ['heading' => 'Changed'])->assertForbidden();
    $this->assertModelExists($item);
    $this->assertDatabaseCount('order_of_services', 0);
});

test('admins can edit homepage names and introductions independently of the schedule', function () {
    $this->actingAs(User::factory()->admin()->create());
    $this->get(route('admin.order-of-service.index'))->assertSuccessful()
        ->assertSeeText('Edit Homepage')->assertSee('Martin Wisener')->assertSee('Lyndsey Herkes');
    $this->patch(route('admin.order-of-service.settings'), ['heading' => 'The big day', 'tagline' => 'Join us']);
    $data = ['partner_one' => 'Alex', 'partner_two' => 'Sam', 'intro' => 'Welcome to our wedding.', 'guest_intro' => 'We cannot wait to see you.'];
    $this->patch(route('admin.order-of-service.homepage'), $data)->assertRedirect(route('admin.order-of-service.index'));
    $this->get(route('home'))->assertSeeTextInOrder(['Alex & Sam', 'Welcome to our wedding.', 'We cannot wait to see you.', 'The big day', 'Join us']);
    $this->get(route('admin.order-of-service.index'))->assertSee('Alex')->assertSee('We cannot wait to see you.');
    $this->patch(route('admin.order-of-service.settings'), ['heading' => 'Our schedule', 'tagline' => null]);
    $this->assertDatabaseHas('order_of_services', $data);
    $this->assertDatabaseCount('order_of_services', 1);
});

test('homepage text edits require admin access and valid fields', function () {
    $url = route('admin.order-of-service.homepage');
    $this->patch($url, [])->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->patch($url, [])->assertForbidden();
    $this->actingAs(User::factory()->admin()->create())->patch($url, [])
        ->assertSessionHasErrors(['partner_one', 'partner_two', 'intro', 'guest_intro']);
    $this->patch($url, ['partner_one' => str_repeat('a', 256), 'partner_two' => 'Sam', 'intro' => str_repeat('a', 5001), 'guest_intro' => 'Hello'])
        ->assertSessionHasErrors(['partner_one', 'intro']);
    $this->assertDatabaseCount('order_of_services', 0);
});
