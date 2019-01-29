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

Route::group(['prefix' => 'v1','namespace' => 'Api'], function () {

Route::get('restaurants' , 'MainController@restaurants');

Route::get('cities' , 'MainController@cities');

Route::get('categories' , 'MainController@categories');

Route::get('offers' , 'MainController@offers');

Route::get('showOffer' , 'MainController@showOffer');

Route::get('products' , 'MainController@products');

Route::post('contacts' , 'MainController@contacts');

Route::get('comments' , 'MainController@comments');

Route::get('restaurantInfo' , 'MainController@restaurantInfo');

Route::get('paymentMethods' , 'MainController@paymentMethods');

Route::post('testNotification' , 'MainController@testNotification');

Route::group(['prefix' => 'client'], function () {

Route::post('register' , 'AuthClientController@register');

Route::post('login' , 'AuthClientController@login');

Route::post('forgotPassword' , 'AuthClientController@forgotPassword');	

Route::post('resetPassword' , 'AuthClientController@resetPassword');

Route::group(['middleware' => 'auth:client'] , function(){

Route::post('registerToken' , 'AuthClientController@registerToken');

Route::post('removeToken' , 'AuthClientController@removeToken');	

Route::post('createOrder' , 'ClientController@createOrder');

Route::get('myOrders' , 'ClientController@myOrders');

Route::post('profile' , 'ClientController@profile');

Route::get('showOrder' , 'ClientController@showOrder');

Route::post('confirmOrder' , 'ClientController@confirmOrder');

Route::post('review' , 'ClientController@review');

Route::get('notifications' , 'ClientController@notifications');

  });

 });


Route::group(['prefix' => 'restaurant'], function () {

Route::post('register' , 'AuthRestaurantController@register');

Route::post('login' , 'AuthRestaurantController@login');

Route::post('forgotPassword' , 'AuthRestaurantController@forgotPassword');	

Route::post('resetPassword' , 'AuthRestaurantController@resetPassword');

Route::group(['middleware' => 'auth:restaurant'] , function(){

Route::post('profile' , 'RestaurantController@profile');	

Route::get('myProducts' , 'RestaurantController@myProducts');

Route::post('addProduct' , 'RestaurantController@addProduct');

Route::post('editProduct' , 'RestaurantController@editProduct');

Route::get('deleteProduct' , 'RestaurantController@deleteProduct');

Route::post('myOrders' , 'RestaurantController@myOrders');

Route::get('showOrder' , 'RestaurantController@showOrder');

Route::post('acceptOrder' , 'RestaurantController@acceptOrder');

Route::post('rejectOrder' , 'RestaurantController@rejectOrder');

Route::post('confirmOrder' , 'RestaurantController@confirmOrder');

Route::post('sendedOrder' , 'RestaurantController@sendedOrder');

Route::get('myOffers' , 'RestaurantController@myOffers');

Route::post('newOffer' , 'RestaurantController@newOffer');

Route::post('editOffer' , 'RestaurantController@editOffer');

Route::get('deleteOffer' , 'RestaurantController@deleteOffer');

Route::post('changeStatus' , 'RestaurantController@changeStatus');

Route::get('notifications' , 'RestaurantController@notifications');


  });

 });

});