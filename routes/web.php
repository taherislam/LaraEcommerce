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

/* ------- Frontend Route Are Here ----------- */

//Home Page...............................
Route::get('/', 'IndexController@Index');

/* -------END Frontend Route Are Here ----------- */
/* ------- Backend Route Are Here ----------- */
// Route::get('/admin', 'AdminController@login');
Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// Category | Listing Page...............................
Route::get('/products/{url}', 'ProductsController@products');


Route::group(['middleware' => ['auth']], function(){
  Route::get('/admin/dashboard', 'AdminController@dashboard');
  Route::get('/admin/settings', 'AdminController@settings');
  Route::get('/admin/check-pwd', 'AdminController@checkPassword');
  Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');

    /* Categtories Routes (Admin) ....................................*/
  Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory');
  Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@editCategory');
  // Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@deletCategory');
  Route::get('/admin/delete-category/{id}', 'CategoryController@deletCategory');
  Route::get('/admin/view-categories', 'CategoryController@viewCategories');

  /* Products Routes (Admin) ....................................*/
  Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@addProduct');
  Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductsController@editProduct');
  Route::get('/admin/view-products', 'ProductsController@viewProducts');
  Route::get('/admin/delete-product/{id}', 'ProductsController@deleteProduct');
  Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage');

/* Products Attributes ....................................*/
  Route::match(['get', 'post'], '/admin/add-attributes/{id}', 'ProductsController@addAttributes');
  Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute');


});

Route::get('/logout', 'AdminController@logout');
