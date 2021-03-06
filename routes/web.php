<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

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

Route::get('/stripe-payment', [StripeController::class, 'handleGet']);
Route::post('/stripe-payment', [StripeController::class, 'handlePost'])->name('stripe.payment');


Route::get('/stripe-paymentV2', [StripeController::class, 'handleShow'])->name('stripe-paymentV2-show');
Route::post('/stripe-paymentV2', [StripeController::class, 'handlePostV2'])->name('stripe.paymentV2');


require __DIR__.'/auth.php';
