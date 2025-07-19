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

Route::post('reservations.cancel', [ReservationController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('reservations.cancel');

// Route::get('/reservations/calendario', function () {
//     return view('reservation.calendario');
// })
//     ->middleware(['auth', 'verified'])
//     ->name('reservations.calendario');

// Route::get('administrador/fullcalendar', [ReservationController::class, 'getAllReservations'])
//     ->middleware(['auth', 'verified'])
//     ->name('administrador.fullcalendar');

// Route::get('/asesor/calendario', function () {
//     return view('asesor.calendario');
// })->middleware(['auth', 'verified'])
//     ->name('asesor.calendario');

// Route::get('asesor/fullcalendar', [ReservationController::class, 'getReservationsAsesor'])
//     ->middleware(['auth', 'verified'])
//     ->name('asesor.fullcalendar');


// Route::get('/cliente/calendario', function () {
//     return view('cliente.calendario');
// })
//     ->middleware(['auth', 'verified'])
//     ->name('cliente.calendario');

// Route::get('cliente/fullcalendar', [ReservationController::class, 'getReservationsCliente'])
//     ->middleware(['auth', 'verified'])
//     ->name('cliente.fullcalendar');

Route::middleware(['auth', 'verified'])->group(function () {
    // Reservas (cliente general)
    Route::view('/reservations/calendario', 'reservation.calendario')->name('reservations.calendario');
    Route::get('administrador/fullcalendar', [ReservationController::class, 'getAllReservations'])->name('administrador.fullcalendar');
    // Route::prefix('administrador')->group(function () {
    //     Route::view('/calendario', 'administrador.calendario')->name('administrador.calendario');
    //     Route::get('/fullcalendar', [ReservationController::class, 'getReservationsAdministrador'])->name('administrador.fullcalendar');
    // });


    // Asesor
    Route::prefix('asesor')->group(function () {
        Route::view('/calendario', 'asesor.calendario')->name('asesor.calendario');
        Route::get('/fullcalendar', [ReservationController::class, 'getReservationsAsesor'])->name('asesor.fullcalendar');
    });

    // Cliente
    Route::prefix('cliente')->group(function () {
        Route::view('/calendario', 'cliente.calendario')->name('cliente.calendario');
        Route::get('/fullcalendar', [ReservationController::class, 'getReservationsCliente'])->name('cliente.fullcalendar');
    });
});

Route::get('cliente/reserva', [ReservationController::class, 'createCliente'])->name('cliente.reserva');
Route::post('/paypal', [ReservationController::class, 'completePayment']);
Route::get('cliente/reservas',[ReservationController::class,'indexcliente'])->name('cliente.reservas');

require __DIR__ . '/auth.php';
