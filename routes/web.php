<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\{
    ProductController,
    CartController,
    OrderController,
    PaymentController,
    ReviewController
};
use App\Http\Controllers\Admin\{
    DashboardController,
    ProductManagementController,
    CategoryManagementController,
    OrderManagementController,
    ReviewManagementController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.items.store');
        Route::patch('/cart/items/{item}', [CartController::class, 'updateItem'])->name('cart.items.update');
        Route::delete('/cart/items/{item}', [CartController::class, 'removeItem'])->name('cart.items.destroy');

        Route::get('checkout', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('checkout', [PaymentController::class, 'processCheckout'])->name('checkout.process');
        Route::get('checkout/success/{order}', [PaymentController::class, 'success'])->name('checkout.success');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

        Route::get('/my-reviews', [ReviewController::class, 'userReviews'])->name('reviews.index');
        Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductManagementController::class);

        Route::resource('categories', CategoryManagementController::class);

        Route::get('orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.update-status');

        Route::get('/reviews', [ReviewManagementController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [ReviewManagementController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
    });
});

require __DIR__.'/auth.php';