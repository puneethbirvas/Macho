<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeliveryController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [UserController::class, 'login']);


//user
Route::post('user/add', [UserController::class, 'store']);
Route::post('user/{id}/update', [UserController::class, 'update']);
Route::post('user/{id}/delete', [UserController::class, 'destroy']);
Route::get('user/showData', [UserController::class, 'showData']);

//vendor
Route::post('vendor/add', [VendorController::class, 'store']);
Route::post('vendor/{id}/update', [VendorController::class, 'update']);
Route::post('vendor/{id}/delete', [VendorController::class, 'destroy']);
Route::get('vendor/showData', [VendorController::class, 'showData']);

//customer
Route::post('customer/add', [CustomerController::class, 'store']);
Route::post('customer/{id}/update', [CustomerController::class, 'update']);
Route::post('customer/{id}/delete', [CustomerController::class, 'destroy']);
Route::get('customer/showData', [CustomerController::class, 'showData']);

//delivery
Route::post('delivery/add', [DeliveryController::class, 'store']);
Route::post('delivery/{id}/update', [DeliveryController::class, 'update']);
Route::post('delivery/{id}/delete', [DeliveryController::class, 'destroy']);
Route::get('delivery/showData', [DeliveryController::class, 'showData']);
Route::get('delivery/getCustomer', [DeliveryController::class, 'getCustomer']);
Route::get('delivery/getVendor', [DeliveryController::class, 'getVendor']);

