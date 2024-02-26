<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ProdTypesController ;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuppliesController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
    
    // Public Route
    Route::post("/register", [ApiController::class, "register"]);
    Route::post("/login", [ApiController::class, "login"]);

   
// Protected Routes with auth:api middleware
  Route::middleware('auth:api')->group( function() {

        Route::get("/profile", [ApiController::class, "profile"]);
        Route::get("/logout", [ApiController::class, "logout"]);

        
        // CRUD IN PRODUCT
     Route::controller(ProductsController::class)->group(function(){
        Route::get("/products", 'index');
        Route::post("/products" ,"storeProduct");
        Route::get('/products/search/{products}', 'searchProducts'); 
        Route::get("/products/id={products}" ,"showProduct");
        Route::get("/products/all_products" ,"showAllProduct");
        Route::put("/products/id={products}/update" ,"updateProduct");
        Route::delete("/products/id={products}/delete" ,"destroyProduct");
        Route::delete("/products/id={products}/softdelete" ,"softdeleterecord");
    });

    // CRUD IN PRODUCT TYPE
    Route::controller(ProdTypesController::class)->group(function(){
        
        Route::get("/product_types", 'index');
        Route::post("/product_types", "storeProductType");
        Route::get('/product_types/search/{product_name}', 'searchProductType'); 
        Route::get("/product_types/id={product_Type}", "showProductType");
        Route::get("/product_types/all_product_types", "showAllProductType");
        Route::put("/product_types/id={product_Type}/update", "updateProductType");
        Route::delete("/product_types/id={product_Type}/delete", "destroyProductType");
        Route::delete("/product_types/id={product_Type}/softdelete", "softdeleterecord");
    });
    
    // CRUD IN SUPPPLIER
    Route::controller(SupplierController::class)->group(function () {
        
        Route::get("/supplier", 'index');
        Route::post("/supplier" , "addSupplier");
        Route::get('/supplier/search/{supplier_name}', 'searchSupplier'); 
        Route::get("/supplier/id={supplier}" , "showSupplier");
        Route::get("/supplier/all_supplier" , "showAllSupplier");
        Route::put("/supplier/id={supplier}/update" , "updateSupplier");
        Route::delete("/supplier/id={supplier}/delete" ,"deleteSupplier");
        Route::delete("/supplier/id={supplier}/softdelete" ,"softdeleterecord");
});

    // CRUD IN SUPPLY
    Route::controller(SuppliesController::class)->group(function () {
        
          Route::get("/supplies", 'index');
          Route::post("/supplies" , "addSupply");
          Route::get('/supplies/search/{supplies}', 'searchSupplies'); 
            Route::put("/supplies/id={supplies}/update" , "updateSupply");
            Route::get("/supplies/id={supplies}" , "showSupplies");
            Route::get("/supplies/all_supply" , "showSuppliesAll");
            Route::delete("/supplies/id={supplies}/delete" , "deleteSupply");
            Route::delete("/supplies/id={supplies}/softdelete" , "softdeleterecord");
        });
       
    });