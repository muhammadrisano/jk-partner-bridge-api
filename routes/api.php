<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/transactions/',[TransactionController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth/register', [RegisterController::class, '__invoke']);
Route::post('/auth/login', [LoginController::class, '__invoke']);
Route::get('/dashboard', [InvoiceController::class, 'dashboard']);


Route::group(['middleware' => ['auth:sanctum']], function () {

	Route::get('/invoice', [InvoiceController::class, 'index']);
    Route::get('/invoice/show/{id}', [InvoiceController::class, 'show']);
    Route::post('/auth/logout', [LogoutController::class, '__invoke']);
});