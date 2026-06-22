<?php

namespace Tests\Feature;

use App\Models\GalleryUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_guest_can_upload_multiple_photos(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('gallery.upload'), [
            'images' => [
                UploadedFile::fake()->image('one.jpg', 2000, 1500),
                UploadedFile::fake()->image('two.png', 1800, 1200),
            ],
        ]);

        $response->assertRedirect(route('gallery'));
        $response->assertSessionHas('status');
        $this->assertDatabaseCount('gallery_uploads', 2);

        $uploads = GalleryUpload::query()->get();

        foreach ($uploads as $upload) {
            Storage::disk('public')->assertExists($upload->original_path);
            Storage::disk('public')->assertExists($upload->display_path);
            $this->assertSame($user->id, $upload->uploaded_by);
            $this->assertFalse($upload->approved);
        }
    }
}
