<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\ProdTypesController ;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
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
        Route::get("/products/index", 'index');
        Route::post("/products" ,"storeProduct");
        Route::get('/products/search/{products}', 'searchProducts'); 
        Route::get("/products" ,"showProduct"); //Show All Products
        Route::get("/products/id={id}" ,"showById"); //Show By ID Products
        Route::get("/products/{id}/all_products/" ,"showSoftDeletedProduct"); // Show Soft Deleted and Non Deleted Products || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::put("/products/id={products}/update" ,"updateProduct");
        Route::delete("/products/id={products}/delete" ,"destroyProduct");
        Route::delete("/products/id={products}/softdelete" ,"softdeleterecord");
    });

    // CRUD IN PRODUCT TYPE
    Route::controller(ProdTypesController::class)->group(function(){
        
        Route::get("/product_types/index", 'index');
        Route::post("/product_types", "storeProductType");
        Route::get('/product_types/search/{product_name}', 'searchProductType'); 
        Route::get("/product_types", "showProductType"); //Show All Products Type
        Route::get("/product_types/id={id}" ,"showById"); //Show By ID Products Type
        Route::get("/product_types/{id}/all_product_types/", "showSoftDeletedProductType"); // Show Soft Deleted and Non Deleted Products Type || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::put("/product_types/id={product_Type}/update", "updateProductType");
        Route::delete("/product_types/id={product_Type}/delete", "destroyProductType");
        Route::delete("/product_types/id={product_Type}/softdelete", "softdeleterecord");
    });
    
    // CRUD IN SUPPPLIER
    Route::controller(SupplierController::class)->group(function () {
        
        Route::get("/supplier/index", 'index');
        Route::post("/supplier" , "addSupplier");
        Route::get('/supplier/search/{supplier_name}', 'searchSupplier'); 
        Route::get("/supplier", "showSupplier"); //Show All Supplier
        Route::get("/supplier/id={id}" , "showById"); //Show All Supplier
        Route::get("/supplier/{id}/all_supplier" , "showSoftDeletedSupplier"); // Show Soft Deleted and Non Deleted Supplier || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::put("/supplier/id={supplier}/update" , "updateSupplier");
        Route::delete("/supplier/id={supplier}/delete" ,"deleteSupplier");
        Route::delete("/supplier/id={supplier}/softdelete" ,"softdeleterecord");
});

    // CRUD IN Interest
    Route::controller(InterestController::class)->group(function(){
        Route::get("/interest/index", 'index');
        Route::post("/interest" ,"storeInterest");
        Route::get('/interest/search/{products}', 'searchInterest'); 
        Route::get("/interest" ,"showInterest"); //Show All Products
        Route::get("/interest/id={id}" ,"showById"); //Show By ID Products
        Route::get("/interest/{id}/all_interest/" ,"showSoftDeletedInterest"); // Show Soft Deleted and Non Deleted Products || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::put("/interest/id={interest}/update" ,"updateInterest");
        Route::delete("/interest/id={interest}/delete" ,"destroyInterest");
        Route::delete("/interest/id={interest}/softdelete" ,"softdeleterecord");
    });

     // CRUD IN Sales
     Route::controller(SalesController::class)->group(function(){
        Route::get("/sales/index", 'index');
        Route::post("/sales" ,"storeSales");
        Route::get('/sales/search/{sales}', 'searchSales'); 
        Route::get("/sales" ,"showSales"); 
        Route::get("/sales/id={id}" ,"showById"); 
        Route::get("/sales/{id}/all_sales/" ,"showSoftDeletedSales"); 
        Route::put("/sales/id={sales}/update" ,"updateSales");
        Route::delete("/sales/id={sales}/delete" ,"destroySales");
        Route::delete("/sales/id={sales}/softdelete" ,"softdeleterecord");
    });
    
    });