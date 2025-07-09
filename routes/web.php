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
Route::middleware(['auth', 'role:user'])->group(function () {
    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/download', [\App\Http\Controllers\TicketController::class, 'downloadTicket'])->name('tickets.download');
    Route::get('/tickets/booking/{booking}/download', [TicketController::class, 'downloadAllTickets'])->name('tickets.download.all');
    Route::get('/tickets/booking/{ticket}/see', [\App\Http\Controllers\TicketController::class, 'see'])->name('tickets.see');

    // Bookings
    Route::get('/booking/screening/{screening}', [BookingController::class, 'create'])->name('booking.create');

    // Payments
    Route::get('/payment/{booking}', [PaymentController::class, 'create'])->name('payment.create');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Ticket Verification (QR)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/admin/tickets/verify', [TicketController::class, 'verify'])->name('admin.tickets.verify');
});

require __DIR__ . '/auth.php';
