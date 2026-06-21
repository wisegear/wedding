<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DiningController as AdminDiningController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GuestController as AdminGuestController;
use App\Http\Controllers\Admin\RsvpController as AdminRsvpController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/venue', VenueController::class)->name('venue');
Route::get('/hotels-and-taxis', TravelController::class)->name('hotels-and-taxis');
Route::get('/gallery', GalleryController::class)->name('gallery');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::post('/gallery/upload', [GalleryController::class, 'store'])->name('gallery.upload');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/guests', [AdminGuestController::class, 'index'])->name('admin.guests.index');
    Route::get('/rsvps', [AdminRsvpController::class, 'index'])->name('admin.rsvps.index');
    Route::get('/dining', [AdminDiningController::class, 'index'])->name('admin.dining.index');
    Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('admin.gallery.index');
});

require __DIR__.'/settings.php';
