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
Route::resource('products', ProductsController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('supplies', SupplierController::class);