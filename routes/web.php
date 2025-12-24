<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->group(function(){
        Route::get('/login', 'loginUser')->name('loginUser');
        Route::post('/login', 'login')->name('login');
        Route::get('/admin/dashboard', 'dashboard')->name('dashboard')->middleware('auth');
    });

Route::controller(BannerController::class)->prefix('api')->group(function () {
    Route::get('/home/banners', 'getBanners')->name('api.banners.get');
});

Route::controller(HomeController::class)->prefix('api')->group(function () {
    Route::get('/best-offers/{limit?}', 'getBestOffers')->name('api.best-offers.get');
    Route::get('/categories', 'getCategories')->name('api.categories.get');
    Route::get('/categories/{id}/products', 'getCategoryProducts')->name('api.categories.products.get');
    Route::get('/search', 'searchProducts')->name('api.search.products');
});

Route::controller(CategoryController::class)->prefix('api')->group(function () {
    Route::get('/categories', 'getCategoriesWithoutIndex')->name('api.getCategoriesWithoutIndex');
    Route::get('/categories/{id}/products', 'getProductsByCategory')->name('api.getProductsByCategory');
});


Route::controller(MarketController::class)->prefix('api')->group(function () {
    Route::get('/markets', 'getMarkets')->name('api.getMarkets');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/about-us', 'aboutUs')->name('about_us');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/returns', 'returns')->name('returns');
    Route::get('/complains', 'complains')->name('complains');
    Route::get('/cart', 'cart')->name('cart');
    Route::get('/{slug}', 'show')->name('show');
    Route::get('/product/{id}', 'product')->name('product.show');
});

// Cart API Routes (for AJAX calls)
Route::controller(CartController::class)->prefix('api')->group(function () {
    Route::get('/cart', 'index')->name('api.cart.index');
    Route::post('/cart/add', 'add')->name('api.cart.add');
    Route::put('/cart/update/{item}', 'update')->name('api.cart.update');
    Route::delete('/cart/remove/{item}', 'remove')->name('api.cart.remove');
    Route::delete('/cart/clear', 'clear')->name('api.cart.clear');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('/admin/orders/store', 'store')->name('orders.store');
});

Route::middleware('auth')
    ->prefix('admin')
    ->group(function () {
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
            Route::post('/logout', 'logout')->name('logout');

        });

    Route::controller(CategoryController::class)
        ->prefix('categories')
        ->name('categories.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-categories', 'getCategories')->name('getCategories');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

    Route::controller(ProductController::class)
        ->prefix('products')
        ->name('products.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-products', 'getProducts')->name('getProducts');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

    Route::controller(OrderController::class)
        ->prefix('orders')
        ->name('orders.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-orders', 'getOrders')->name('getOrders');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

    Route::controller(BannerController::class)
        ->prefix('banners')
        ->name('banners.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-banners', 'getBanners')->name('getBanners');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

    Route::controller(MarketController::class)
        ->prefix('markets')
        ->name('markets.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-markets', 'getMarkets')->name('getMarkets');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

    Route::controller(UserController::class)
        ->prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-users', 'getUsers')->name('getUsers');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });
});
