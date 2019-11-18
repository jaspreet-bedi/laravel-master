<?php

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
Route::get('/', 'Frontend\HomeController@index');

Route::get('/welcome',[
   'uses' => 'WelcomeController@index'
]);

Route::get('users', 'UsersController@index');

Route::get('users/createUser', 'UsersController@createUser');

Route::post('/users/saveUser', 'UsersController@saveUser');

Route::get('/departments/createDepartment', 'DepartmentsController@createDepartment');

Route::post('/users/saveDepartment', 'DepartmentsController@saveDepartment');

Route::get('/categories/createCategory', 'CategoriesController@createCategory');

Route::post('/categories/createCategory', 'CategoriesController@saveCategory');

Route::get('/subcategories/createSubcategory', 'SubcategoriesController@createSubcategory');

Route::post('/subcategories/createSubcategory', 'SubCategoriesController@saveSubcategory');

Route::get('/products/createProduct', 'ProductsController@createProduct');

Route::post('/products/createProduct', 'ProductsController@saveProduct');

Route::get('/products/uploadImage', 'ProductsController@uploadImage');

Route::post('/products/storeImage', 'ProductsController@storeImage');

Route::post('/products/uploadProductImage/{product_id}', 'ProductsController@uploadProductImage');

##########################################################################

Route::get('/frontend/home', 'Frontend\HomeController@index');

//Route::post('/frontend/home', 'Frontend\HomeController@index');

Route::get('/frontend/product/showProduct/{id}', 'Frontend\ProductController@showProduct');

Route::get('/frontend/account', 'Frontend\AccountController@index');

Route::post('/frontend/account/registerUser', 'Frontend\AccountController@registerUser');

Route::post('/frontend/account/updateAccountDetails', 'Frontend\AccountController@updateAccountDetails');

Route::get('/frontend/account/showAccountDetails', 'Frontend\AccountController@showAccountDetails');

Route::get('/frontend/account/authenticateUser', 'Frontend\AccountController@authenticateUser');

//Route::get('/frontend/cart/addToCart/{id}', 'Frontend\CartController@addToCart');
Route::get('/frontend/cart/addToCart', 'Frontend\CartController@addToCart');

Route::get('/frontend/shopgrid/{subcat_id}', 'Frontend\ShopgridController@index');

Route::get('/frontend/cart/showCart', 'Frontend\CartController@showCart');

Route::get('/frontend/cart/updateCart', 'Frontend\CartController@updateCart');

Route::get('/frontend/cart/applyCoupon', 'Frontend\CartController@applyCoupon');

Route::get('/frontend/cart/checkout', 'Frontend\CartController@checkout');

//Route::get('/frontend/cart/deleteItem/{custid}/{prodid}', 'Frontend\CartController@deleteItem');
Route::post('/frontend/cart/deleteItem', 'Frontend\CartController@deleteItem');

Route::get('/frontend/order/insertOrder', 'Frontend\OrderController@insertOrder');

Route::get('/frontend/order/processPaymentMethod', 'Frontend\OrderController@processPaymentMethod');

Route::get('/frontend/order/saveShipmentAddressInSession', 'Frontend\OrderController@saveShipmentAddressInSession');

Route::get('/frontend/order/index', 'Frontend\OrderController@index');

Route::get('/frontend/order/selectPaymentMethod', 'Frontend\OrderController@selectPaymentMethod');

Route::get('/frontend/order/placeOrder', 'Frontend\OrderController@placeOrder');

Route::get('/frontend/order/order_detail_page/{order}', 'Frontend\OrderController@order_detail_page');

Route::get('/frontend/account/signout', 'Frontend\AccountController@signout');

Route::get('/frontend/stripe', 'Frontend\StripePaymentController@stripe');

Route::post('/frontend/stripe', 'Frontend\StripePaymentController@stripePost')->name('stripe.post');

Route::get('/frontend/shippo', 'Frontend\ShippoController@index');

Route::get('/frontend/shippo/store', 'Frontend\ShippoController@store');












