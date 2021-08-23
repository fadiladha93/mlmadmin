<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('payment')->group(function() {
    Route::post('/update/{id}', 'API\PaymentMethodController@update');
});

Route::get('/validate-boom/{code}','API\BoomerangController@validateCode');
Route::get('/boomerang-tracker/{trackerId}','API\BoomerangController@index');
Route::post('/confirm-user','API\BoomerangController@confirmUser');
Route::post('/store-igo-user','API\CustomerController@storeIgoUser');

Route::middleware('check.api_credentials')->group(function() {
    Route::get('/lookup/type/{type}/value/{value}', 'External\LookupController@index');
    Route::post('/user', 'External\UserController@store');
    Route::post('/customer', 'External\CustomerController@store');

    //payments
    Route::post('/payment-authorize', 'External\PaymentController@authorizePayment');
    Route::post('/payment-capture', 'External\PaymentController@capture');
    Route::post('/payment', 'External\PaymentController@store');
});
