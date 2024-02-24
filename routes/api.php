<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ProdTController;
use App\Http\Controllers\ProdTypesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductTypesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuppliesController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::post("/register", [ApiController::class, "register"]);
    Route::post("/login", [ApiController::class, "login"]);



    // CRUD IN PRODUCT
    Route::controller(ProductsController::class)->group(function(){

        Route::get("/index/Products", 'index');
        Route::post("/storeProduct" ,"storeProduct");
        Route::get("/showProduct/products/id={products}" ,"showProduct");
        Route::get("/showAllProduct/products" ,"showAllProduct");
        Route::put("/updateProduct/products/id={products}" ,"updateProduct");
        Route::delete("/destroyProduct/products/id={products}" ,"destroyProduct");
    });

    //CRUD IN PRODUCT TYPE
    Route::controller(ProdTypesController::class)->group(function(){

        Route::get("/index/ProductType", 'index');
        Route::post("/storeProductType", "storeProductType");
        Route::get("/showProductType/ProductType/id={product_Type}", "showProductType");
        Route::get("/showAllProductType", "showAllProductType");
        Route::put("/updateProductType/ProductType/id={product_Type}", "updateProductType");
        Route::delete("/destroyProductType/ProductType/id={product_Type}", "destroyProductType");
    });
    //    Route::group( ['middleware' => ['auth', 'role:administrator']], function(){
    //     Route::post("/storeProduct" ,"storeProduct");
    //     Route::get("/showProduct/products/id={products}" ,"showProduct");
    //     Route::get("/showAllProduct/products" ,"showAllProduct");
    //     Route::put("/updateProduct/products/id={products}" ,"updateProduct");
    //     Route::delete("/destroyProduct/products/id={products}" ,"destroyProduct");
    // });
    

    // CRUD IN SUPPPLIER
    Route::controller(SupplierController::class)->group(function () {
        
        Route::get("/index/Supplier", 'index');
        Route::post("/addSupplier/user" , "addSupplier");
        Route::get("/showSupplier/supplier/id={supplier}" , "showSupplier");
        Route::get("/showAllSupplier/supplier" , "showAllSupplier");
        Route::put("/updateSupplier/supplier/id={supplier}" , "updateSupplier");
        Route::delete("/deleteSupplier/supplier/id={supplier}" ,"deleteSupplier");
});

    // CRUD IN SUPPLY
    Route::controller(SuppliesController::class)->group(function () {

          Route::get("/index/Supplies", 'index');
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
