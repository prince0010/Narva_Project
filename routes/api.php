<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuppliesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Syntax: <Project URL>/api/register = Prefix
// Open Routes
    // we dont need login or token before accessing
    // The Route::post("register" -> is you can call it for the URL in the Prefix and Use it for the POSTMAN like http://127.0.0.1:8000/api/register
    // [ApiController::class, "register"] - The "register" is the Method in this class
    Route::post("/register", [ApiController::class, "register"]);
    Route::post("/login", [ApiController::class, "login"]);

    // CRUD IN PRODUCT
    Route::post("/store" , [ProductsController::class, "store"]);
    Route::get("/show/products/id={products}" , [ProductsController::class, "show"]);
    Route::get("/showAll/products" , [ProductsController::class, "showAll"]);
    Route::put("/update/products/id={products}" , [ProductsController::class, "update"]);
    Route::delete("/destroy/products/id={products}" , [ProductsController::class, "destroy"]);
    

    // CRUD IN SUPPPLIER
    Route::post("/addSupplier/user" , [SupplierController::class, "addSupplier"]);
    Route::get("/show/supplier/id={supplier}" , [SupplierController::class, "show"]);
    Route::get("/showAll/supplier" , [SupplierController::class, "showAll"]);
    Route::put("/update/supplier/id={supplier}" , [SupplierController::class, "update"]);
    Route::delete("/delete/supplier/id={supplier}" , [SupplierController::class, "delete"]);

    // CRUD IN SUPPLY
    Route::post("/addSupply/supply" , [SuppliesController::class, "addSupply"]);

// Protected Routes with auth:api miiddleware
    Route::group(
        // 1st parameter
        [
        // Keycall middleware 
            "middleware" => ["auth:api"]
      
            // 2nd Parameter
             // call back function
         ], 
    function(){
        Route::get("/profile", [ApiController::class, "profile"]);
        Route::get("/logout", [ApiController::class, "logout"]);
    }

    );
