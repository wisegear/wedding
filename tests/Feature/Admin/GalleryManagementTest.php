<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_gallery_page_lists_pending_and_approved_uploads(): void
    {
        $admin = User::factory()->admin()->create();
        $guest = User::factory()->create([
            'name' => 'Lee Wisener',
        ]);

        GalleryUpload::query()->create([
            'uploaded_by' => $guest->id,
            'original_filename' => 'pending-photo.jpg',
            'original_path' => 'gallery/originals/pending-photo.jpg',
            'display_path' => 'gallery/display/pending-photo.jpg',
            'approved' => false,
        ]);

        GalleryUpload::query()->create([
            'uploaded_by' => $guest->id,
            'original_filename' => 'approved-photo.jpg',
            'original_path' => 'gallery/originals/approved-photo.jpg',
            'display_path' => 'gallery/display/approved-photo.jpg',
            'approved' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.gallery.index'));

        $response->assertOk();
        $response->assertSee('pending-photo.jpg');
        $response->assertSee('approved-photo.jpg');
        $response->assertSee('Lee Wisener');
    }

    public function test_admin_can_approve_gallery_upload(): void
    {
        $admin = User::factory()->admin()->create();

        $upload = GalleryUpload::query()->create([
            'original_filename' => 'pending-photo.jpg',
            'original_path' => 'gallery/originals/pending-photo.jpg',
            'display_path' => 'gallery/display/pending-photo.jpg',
            'approved' => false,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.gallery.approve', $upload));

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseHas('gallery_uploads', [
            'id' => $upload->id,
            'approved' => true,
        ]);
    }

    public function test_admin_can_remove_gallery_upload_from_public_gallery(): void
    {
        $admin = User::factory()->admin()->create();

        $upload = GalleryUpload::query()->create([
            'original_filename' => 'approved-photo.jpg',
            'original_path' => 'gallery/originals/approved-photo.jpg',
            'display_path' => 'gallery/display/approved-photo.jpg',
            'approved' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.gallery.unapprove', $upload));

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseHas('gallery_uploads', [
            'id' => $upload->id,
            'approved' => false,
        ]);
    }
}
