<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DiningController as AdminDiningController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GuestGroupController as AdminGuestGroupController;
use App\Http\Controllers\Admin\RsvpController as AdminRsvpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuestDiningController;
use App\Http\Controllers\GuestRsvpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/venue', VenueController::class)->name('venue');
Route::get('/hotels-and-taxis', TravelController::class)->name('hotels-and-taxis');
Route::get('/gallery', GalleryController::class)->name('gallery');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('/rsvp', [GuestRsvpController::class, 'edit'])->name('rsvp.edit');
    Route::put('/rsvp', [GuestRsvpController::class, 'update'])->name('rsvp.update');
    Route::get('/dining', [GuestDiningController::class, 'edit'])->name('dining.edit');
    Route::put('/dining', [GuestDiningController::class, 'update'])->name('dining.update');
    Route::post('/gallery/upload', [GalleryController::class, 'store'])->name('gallery.upload');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/guest-groups', [AdminGuestGroupController::class, 'index'])->name('admin.guest-groups.index');
    Route::get('/guest-groups/create', [AdminGuestGroupController::class, 'create'])->name('admin.guest-groups.create');
    Route::post('/guest-groups', [AdminGuestGroupController::class, 'store'])->name('admin.guest-groups.store');
    Route::get('/guest-groups/{guestGroup}/edit', [AdminGuestGroupController::class, 'edit'])->name('admin.guest-groups.edit');
    Route::match(['put', 'patch'], '/guest-groups/{guestGroup}', [AdminGuestGroupController::class, 'update'])->name('admin.guest-groups.update');
    Route::delete('/guest-groups/{guestGroup}', [AdminGuestGroupController::class, 'destroy'])->name('admin.guest-groups.destroy');
    Route::get('/rsvps', [AdminRsvpController::class, 'index'])->name('admin.rsvps.index');
    Route::get('/dining', [AdminDiningController::class, 'index'])->name('admin.dining.index');
    Route::get('/dining/selections', [AdminDiningController::class, 'selections'])->name('admin.dining.selections');
    Route::post('/dining', [AdminDiningController::class, 'store'])->name('admin.dining.store');
    Route::match(['put', 'patch'], '/dining/{diningOption}', [AdminDiningController::class, 'update'])->name('admin.dining.update');
    Route::delete('/dining/{diningOption}', [AdminDiningController::class, 'destroy'])->name('admin.dining.destroy');
    Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('admin.gallery.index');
    Route::patch('/gallery/{galleryUpload}/approve', [AdminGalleryController::class, 'approve'])->name('admin.gallery.approve');
    Route::patch('/gallery/{galleryUpload}/unapprove', [AdminGalleryController::class, 'unapprove'])->name('admin.gallery.unapprove');
});

require __DIR__.'/settings.php';
