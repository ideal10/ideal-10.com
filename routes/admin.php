<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ComponenteController;
use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.nav-items.index');
    })->name('dashboard');

    Route::get('site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
    Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');

    Route::resource('nav-items', NavItemController::class)->except(['show']);
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::resource('entities', EntityController::class)->except(['show']);
    Route::resource('componentes', ComponenteController::class)->except(['show']);
});
