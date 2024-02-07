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
    Route::controller(ProductsController::class)->group(function(){
        Route::post("/storeProduct" ,"storeProduct");
        Route::get("/showProduct/products/id={products}" ,"showProduct");
        Route::get("/showAllProduct/products" ,"showAllProduct");
        Route::put("/updateProduct/products/id={products}" ,"updateProduct");
        Route::delete("/destroyProduct/products/id={products}" ,"destroyProduct");
    });
   
    

    // CRUD IN SUPPPLIER
    Route::controller(SupplierController::class)->group(function () {
        Route::post("/addSupplier/user" ,  "addSupplier");
        Route::get("/showSupplier/supplier/id={supplier}" , "showSupplier");
        Route::get("/showAllSupplier/supplier" , "showAllSupplier");
        Route::put("/updateSupplier/supplier/id={supplier}" , "updateSupplier");
        Route::delete("/deleteSupplier/supplier/id={supplier}" ,"deleteSupplier");
});

    // CRUD IN SUPPLY
    Route::controller(SuppliesController::class)->group(function () {
            Route::post("/addSupply/supply" , "addSupply");
            Route::put("/updateSupply/supply/id={supplies}" , "updateSupply");
            Route::get("/showSupplies/supply/id={supplies}" , "showSupplies");
            Route::get("/showSuppliesAll/supply" , "showSuppliesAll");
            Route::delete("/deleteSupply/supply/id={supplies}" , "deleteSupply");
        });
   
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
