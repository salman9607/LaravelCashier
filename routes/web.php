<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => 'auth'], function (){
    Route::get('billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing');
    Route::get('checkout/{plan_id}', [\App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('checkout', [\App\Http\Controllers\CheckoutController::class, 'processCheckout'])->name('checkout.process');

    Route::get('cancel', [\App\Http\Controllers\BillingController::class, 'cancel'])->name('cancel');
    Route::get('resume', [\App\Http\Controllers\BillingController::class, 'resu me'])->name('resume');

    Route::get('markDefault/{methodId}', [\App\Http\Controllers\PaymentMethodController::class, 'markDefault'])->name('markDefault');
    Route::resource('payment-method', \App\Http\Controllers\PaymentMethodController::class);
});

Route::stripeWebhooks('stripe-webhook');

require __DIR__.'/auth.php';
