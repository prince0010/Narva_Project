<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Resource Route for Crud   
// Route::resource('products', ProductsController::class);
// Route::resource('supplier', SupplierController::class);
// Route::resource('supplies', SupplierController::class);


// Login/Register/Profile
Route::get('/registerUser', 'PagesController@registerUser'); 
Route::get('/loginUser', 'PagesController@loginUser'); 
Route::get('/profileUser', 'PagesController@profileUser'); 
// Route::get('/logoutUser', 'PagesController@logoutUser');


// Product
Route::get('Productindex', 'PagesController@Productindex');
Route::get('Productindex', 'PagesController@Productindex');
Route::get('Productindex', 'PagesController@Productindex');

// Supplier
Route::get('/Supplierindex', 'PagesController@Supplierindex');
Route::get('/createSupplier', 'PagesController@createSupplier');
Route::get('/editSupplier', 'PagesController@editSupplier');

// Supplies / Inventory
Route::get('/Suppliesindex', 'PagesController@Suppliesindex'); 
Route::get('/createSupplies', 'PagesController@createSupplies');
Route::get('/editSupplies', 'PagesController@editSupplies');

