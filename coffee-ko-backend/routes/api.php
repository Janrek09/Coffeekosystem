<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

// Protected routes (only for logged in users)
Route::middleware('auth:sanctum')->group(function () {
    // 📊 Dashboard
    Route::get('/summary', [DashboardController::class, 'summary']);
    Route::get('/sales/weekly', [DashboardController::class, 'weeklySales']);
    Route::get('/products/bestsellers', [DashboardController::class, 'bestSellers']);
    Route::get('/events', [DashboardController::class, 'events']);

    // ✅ Ingredients box endpoint (dashboard auto-updates)
    Route::get('/dashboard/ingredients', [DashboardController::class, 'ingredientsBox']);

    // 📦 Inventory (RESTful endpoints)
    Route::get('/inventories', [InventoryController::class, 'index']);             // Get all items
    Route::get('/inventories/{inventory}', [InventoryController::class, 'show']);  // Get single item
    Route::post('/inventories', [InventoryController::class, 'store']);            // Add new item
    Route::put('/inventories/{inventory}', [InventoryController::class, 'update']); // Update item
    Route::delete('/inventories/{inventory}', [InventoryController::class, 'destroy']); // Delete item

    // 📑 Reports
    Route::get('/reports/sales', [ReportController::class, 'sales']);
});
