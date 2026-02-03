<?php

use App\Http\Controllers\Web\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Web\Admin\Catalogue\CategoriesController as AdminCategoriesController;
use App\Http\Controllers\Web\Admin\Catalogue\SubCategoriesController as AdminSubCategoriesController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\LocationProxyController;
use App\Http\Controllers\Web\Admin\ListingsController as AdminListingsController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ListingController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['setLocale'])->group(function () {
    Route::get('/locale/{locale}', function (string $locale) {
        $supported = array_keys((array) config('app.supported_locales', []));

        if (!empty($supported) && in_array($locale, $supported, true)) {
            session(['locale' => $locale]);
        }

        return redirect()->to(url()->previous() ?: route('web.home'));
    })->name('web.locale.switch');

    Route::get('/', [HomeController::class, 'index'])->name('web.home');
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('web.category.show');
    Route::get('/listing/{slug}', [ListingController::class, 'show'])->name('web.listing.show');
    Route::get('/search', [SearchController::class, 'index'])->name('web.search');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('web.checkout');

    Route::get('/orders', [OrdersController::class, 'index'])->name('web.orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('web.orders.show');

    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('web.logout');

    Route::prefix('admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::middleware(['web.auth', 'web.admin'])->group(function () {
            Route::get('/', function () {
                return redirect()->route('admin.overview');
            })->name('admin.home');

            Route::get('/overview', [DashboardController::class, 'overview'])->name('admin.overview');
            Route::get('/products', function () {
                return redirect()->route('admin.listings.index');
            })->name('admin.products');
            Route::get('/sales', [DashboardController::class, 'sales'])->name('admin.sales');
            Route::get('/orders', [DashboardController::class, 'orders'])->name('admin.orders');
            Route::get('/deliveries', [DashboardController::class, 'deliveries'])->name('admin.deliveries');
            Route::get('/saved', [DashboardController::class, 'saved'])->name('admin.saved');

            Route::get('/api/locations/states/{stateSlug}/cities', [LocationProxyController::class, 'cities'])
                ->name('admin.api.locations.cities');

            Route::get('/listings', [AdminListingsController::class, 'index'])->name('admin.listings.index');
            Route::get('/listings/create', [AdminListingsController::class, 'create'])->name('admin.listings.create');
            Route::post('/listings', [AdminListingsController::class, 'store'])->name('admin.listings.store');
            Route::get('/listings/{id}/edit', [AdminListingsController::class, 'edit'])->name('admin.listings.edit');
            Route::post('/listings/{id}', [AdminListingsController::class, 'update'])->name('admin.listings.update');
            Route::post('/listings/{id}/status', [AdminListingsController::class, 'updateStatus'])->name('admin.listings.status');

            Route::get('/catalogue/categories', [AdminCategoriesController::class, 'index'])->name('admin.catalogue.categories.index');
            Route::get('/catalogue/categories/{category}', [AdminCategoriesController::class, 'show'])->name('admin.catalogue.categories.show');

            Route::get('/catalogue/subcategories', [AdminSubCategoriesController::class, 'index'])->name('admin.catalogue.subcategories.index');
            Route::get('/catalogue/subcategories/{subCategory}', [AdminSubCategoriesController::class, 'show'])->name('admin.catalogue.subcategories.show');
        });
    });
});

