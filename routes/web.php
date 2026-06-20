<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BookingController;
use App\Http\Middleware\EnsureUserBelongsToTenant;
use App\Http\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;

Route::domain('{tenant}.localhost')
    ->middleware([
        TenantMiddleware::class,
        EnsureUserBelongsToTenant::class,
    ])
    ->group(function () {
        Route::get('/', [BookingController::class, 'landing'])->name('tenant.landing');
        Route::get('/book', [BookingController::class, 'index'])->name('tenant.booking');

        Route::get('/api/barbers', [BookingController::class, 'barbers']);
        Route::get('/api/services', [BookingController::class, 'services']);
        Route::get('/api/availability', [BookingController::class, 'availability']);

        Route::get('/api/appointments', [AppointmentController::class, 'index']);
        Route::post('/api/appointments', [AppointmentController::class, 'store']);
    });
