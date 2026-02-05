<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\OutcomeController;
use App\Http\Controllers\Api\SeatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Records API Routes
Route::prefix('records')->group(function () {
    Route::get('/', [RecordController::class, 'index']);
    Route::post('/', [RecordController::class, 'store']);
    Route::get('/{record}', [RecordController::class, 'show']);
    Route::put('/{record}', [RecordController::class, 'update']);
    Route::patch('/{record}', [RecordController::class, 'update']);
    Route::delete('/{record}', [RecordController::class, 'destroy']);
});

// Seats API
Route::get('/seats', [SeatController::class, 'index']);

// Shortcut route for total top members_amount
Route::get('/top-members', [RecordController::class, 'topMembers']);


// Inventory API Routes
Route::prefix('inventories')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::post('/', [InventoryController::class, 'store']);
    Route::get('/{inventory}', [InventoryController::class, 'show']);
    Route::put('/{inventory}', [InventoryController::class, 'update']);
    Route::patch('/{inventory}', [InventoryController::class, 'update']);
    Route::delete('/{inventory}', [InventoryController::class, 'destroy']);
    Route::post('/{inventory}/update-quantity', [InventoryController::class, 'updateQuantity']);
});

// Outcomes API Routes
Route::prefix('outcomes')->group(function () {
    Route::get('/', [OutcomeController::class, 'index']);
    Route::post('/', [OutcomeController::class, 'store']);
    Route::get('/total', [OutcomeController::class, 'total']);
    Route::get('/{outcome}', [OutcomeController::class, 'show']);
    Route::put('/{outcome}', [OutcomeController::class, 'update']);
    Route::patch('/{outcome}', [OutcomeController::class, 'update']);
    Route::delete('/{outcome}', [OutcomeController::class, 'destroy']);
});
