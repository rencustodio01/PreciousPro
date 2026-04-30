<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceRecordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualityInspectionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    // Dashboard — all authenticated users
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management — Admin only
    Route::resource('users', UserController::class)
        ->middleware('role:Admin');

    // Role Management — Admin only
    Route::resource('roles', RoleController::class)
        ->middleware('role:Admin');

    // Category Management — Admin, Production Manager
    Route::resource('categories', CategoryController::class)
        ->middleware('role:Admin,Production Manager');

    // Product Management — Admin, Production Manager
    Route::resource('products', ProductController::class)
        ->middleware('role:Admin,Production Manager');

    // Production Management — Admin, Production Manager
    Route::resource('production', ProductionController::class)
        ->middleware('role:Admin,Production Manager');

    // Quality Inspection — Admin, QC Officer
    Route::resource('quality', QualityInspectionController::class)
        ->parameters(['quality' => 'qualityInspection'])
        ->middleware('role:Admin,QC Officer');

    // Inventory — Admin, Inventory Officer
    Route::middleware('role:Admin,Inventory Officer')->group(function () {
        Route::post('inventory/transaction', [InventoryController::class, 'addTransaction'])
             ->name('inventory.transaction');
        Route::resource('inventory', InventoryController::class)
            ->only(['index', 'show']);
    });

    // Finance Records — Admin, Finance Officer
    Route::resource('finance', FinanceRecordController::class)
        ->parameters(['finance' => 'financeRecord'])
        ->middleware('role:Admin,Finance Officer');
});