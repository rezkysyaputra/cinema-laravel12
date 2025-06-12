<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // Bookings
    Route::get('/booking/screening/{screening}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/screening/{screening}', [BookingController::class, 'store'])->name('booking.store');

    // Payments
    Route::get('/payment/booking/{booking}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/booking/{booking}', [PaymentController::class, 'store'])->name('payment.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
