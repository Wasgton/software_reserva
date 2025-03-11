<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resources([
    'properties' => PropertyController::class,
    'owners' => OwnerController::class,
    'guests' => GuestController::class,
    'reservations' => ReservationController::class,
]);

Route::prefix('financial')->group(function () {
    Route::get('/', [FinancialController::class, 'index'])->name('financial.index');
    Route::get('/transactions', [FinancialController::class, 'transactions'])->name('financial.transactions');
    Route::post('/transactions', [FinancialController::class, 'storeTransaction'])->name('financial.transactions.store');
});

Route::prefix('reports')->group(function () {
    Route::get('/owners', [ReportController::class, 'owners'])->name('reports.owners');
    Route::get('/owners/{owner}/export', [ReportController::class, 'exportOwnerReport'])->name('reports.owners.export');
});
