<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\ListingsController;
use App\Http\Controllers\Api\V1\LocationsController;
use App\Http\Controllers\Api\V1\SavedListingsController;
use App\Http\Controllers\Api\V1\Admin\ListingsController as AdminListingsController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json(['status' => 'ok']);
    });

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'apiRedirect']);
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'apiCallback']);

    Route::get('/listings', [ListingsController::class, 'index']);
    Route::get('/listings/{slug}', [ListingsController::class, 'show']);

    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/categories/{categorySlug}/subcategories', [CategoriesController::class, 'subCategories']);

    Route::get('/locations/states', [LocationsController::class, 'states']);
    Route::get('/locations/states/{stateSlug}/cities', [LocationsController::class, 'cities']);

    Route::middleware([StartSession::class])->group(function () {
        Route::get('/cart', [CartController::class, 'show']);
        Route::post('/cart/items', [CartController::class, 'storeItem']);
        Route::patch('/cart/items/{id}', [CartController::class, 'updateItem']);
        Route::delete('/cart/items/{id}', [CartController::class, 'destroyItem']);
        Route::delete('/cart/clear', [CartController::class, 'clear']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/saved-listings', [SavedListingsController::class, 'index']);
        Route::post('/saved-listings', [SavedListingsController::class, 'store']);
        Route::delete('/saved-listings/{listingId}', [SavedListingsController::class, 'destroy']);

        Route::prefix('admin')->middleware('admin')->group(function () {
            Route::post('/listings', [AdminListingsController::class, 'store']);
            Route::put('/listings/{listing}', [AdminListingsController::class, 'update']);
            Route::delete('/listings/{listing}', [AdminListingsController::class, 'destroy']);
            Route::post('/listings/{listing}/images', [AdminListingsController::class, 'uploadImages']);
            Route::patch('/listings/{listing}/status', [AdminListingsController::class, 'updateStatus']);
        });
    });
});
