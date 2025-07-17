<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('usuarios', UserController::class)
    ->middleware(['auth', 'verified'])
    ->names('usuarios');

Route::resource('reservas', ReservationController::class)
    ->middleware(['auth', 'verified'])
    ->names('reservations');

Route::post('reservations.cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

Route::get('/reservations/calendario', function () {
    return view('reservation.calendario');
})->name('reservations.calendario');

Route::get('administrador/fullcalendar', [ReservationController::class, 'getAllReservations'])->name('administrador.fullcalendar');

require __DIR__ . '/auth.php';
