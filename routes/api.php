<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\OutcomeController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\PricingController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Public)
|--------------------------------------------------------------------------
*/

// LOGIN (Public) -> returns token
Route::post('/login', function (Request $request) {

    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
        'device_name' => ['nullable', 'string'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Optional: Remove old tokens (force one active token per user)
    // $user->tokens()->delete();

    $tokenName = $request->device_name ?? 'api-token';
    $token = $user->createToken($tokenName)->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => $user,
    ]);
});


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Require Bearer Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // LOGOUT -> revoke current token
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    // CURRENT LOGGED IN USER
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    /*
    |--------------------------------------------------------------------------
    | Your APIs (Protected)
    |--------------------------------------------------------------------------
    */

    // Records API Routes
    Route::apiResource('records', RecordController::class);
    Route::get('/top-members', [RecordController::class, 'topMembers']);
    Route::get('/top-debtors', [RecordController::class, 'topDebtors']);

    // Inventory API Routes
    Route::post('/inventories/{inventory}/quantity', [InventoryController::class, 'updateQuantity']);
    Route::apiResource('inventories', InventoryController::class);

    // Outcomes API Routes
    Route::get('/outcomes/total', [OutcomeController::class, 'total']);
    Route::apiResource('outcomes', OutcomeController::class);

    // Protected Pricing & Announcements (Create, Update, Delete)
    Route::apiResource('pricing', PricingController::class)->except(['index', 'show']);
    Route::apiResource('announcements', AnnouncementController::class)->except(['index', 'show']);

});

// Pricing API Routes (Public Read)
Route::get('/pricing', [PricingController::class, 'index']);
Route::get('/pricing/{pricing}', [PricingController::class, 'show']);

// Seats API to show online status
Route::get('/seats', [SeatController::class, 'index']);

// Announcements API Routes (Public Read)
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);
