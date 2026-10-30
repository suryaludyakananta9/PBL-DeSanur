<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\AdminController;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/explore', [PublicController::class, 'explore'])->name('explore');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::get('/destinasi/{slug}', [PublicController::class, 'detail'])->name('destination.detail');
Route::get('/rekomendasi', [PublicController::class, 'recommendation'])->name('recommendation');
Route::get('/trip-planner', [PublicController::class, 'planner'])->name('planner');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('traveler')->name('traveler.')->group(function () {
    Route::get('/favorites', [TravelerController::class, 'favorites'])->name('favorites');
    Route::post('/destinasi/{destination}/favorite', [TravelerController::class, 'toggleFavorite'])->name('favorite.toggle');
    Route::post('/destinasi/{destination}/review', [TravelerController::class, 'review'])->name('review.store');
    Route::get('/itineraries', [TravelerController::class, 'itineraries'])->name('itineraries');
    Route::post('/itineraries', [TravelerController::class, 'storeItinerary'])->name('itinerary.store');
    Route::delete('/itineraries/{itinerary}', [TravelerController::class, 'deleteItinerary'])->name('itinerary.delete');
    Route::get('/profile', [TravelerController::class, 'profile'])->name('profile');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/destinations', [AdminController::class, 'destinations'])->name('destinations');
    Route::post('/destinations', [AdminController::class, 'storeDestination'])->name('destinations.store');
    Route::put('/destinations/{destination}', [AdminController::class, 'updateDestination'])->name('destinations.update');
    Route::delete('/destinations/{destination}', [AdminController::class, 'deleteDestination'])->name('destinations.delete');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    Route::get('/event-kuliner', [AdminController::class, 'eventKuliner'])->name('event-kuliner');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::patch('/reviews/{review}', [AdminController::class, 'moderateReview'])->name('reviews.moderate');
});
