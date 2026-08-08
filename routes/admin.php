<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ComponenteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\InterestLinkController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('nav-items', NavItemController::class)->except(['show']);
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::resource('entities', EntityController::class)->except(['show']);
    Route::resource('componentes', ComponenteController::class)->except(['show']);

    Route::resource('interest-links', InterestLinkController::class)->except(['show']);
    Route::patch('interest-links/{interest_link}/toggle', [InterestLinkController::class, 'toggle'])->name('interest-links.toggle');
    Route::post('interest-links/reorder', [InterestLinkController::class, 'reorder'])->name('interest-links.reorder');

    Route::resource('users', UserController::class)->except(['show']);
});
