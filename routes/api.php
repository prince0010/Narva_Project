<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\CreditInfoController;
use App\Http\Controllers\CreditNamesController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\MarkupController;
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
        Route::post("products/id={products}/subtract-stock", "subtractStock");
        Route::post("products/id={products}/add-stock", "addStock");
        Route::get("/products/top-low-stock-products", "lowestStock");
        Route::get("/products/outofstock", "outofStock");
        Route::get("/products/import-form", "import");
        Route::post("/products/import" , "import");
        Route::post("/products/add-quantites", "addQuantities");
        Route::post('/products/{productId}/stock/{quantity}', 'addStockbyID'); 
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

    // CRUD IN Markup
    Route::controller(MarkupController::class)->group(function(){
        Route::get("/markup/index", 'index');
        Route::post("/markup" ,"storeMarkup");
        Route::get('/markup/search/{markup}', 'searchMarkup'); 
        Route::get("/markup" ,"showMarkup"); //Show All Products
        Route::get("/markup/id={id}" ,"showById"); //Show By ID Products
        Route::get("/markup/{id}/all_markup/" ,"showSoftDeletedMarkup"); // Show Soft Deleted and Non Deleted Products || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::put("/markup/id={markup}/update" ,"updateMarkup");
        Route::delete("/markup/id={markup}/delete" ,"destroyMarkup");
        Route::delete("/markup/id={markup}/softdelete" ,"softdeleterecord");
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
        Route::get('/sales/top-products', 'getTopProducts');
        Route::get('/sales/{yearly}/top-products', 'getTopProducts'); //Yearly Reports
        Route::get('/sales/{yearly}/{monthly}/top-products', 'getTopProducts'); //Monthly Reports
        Route::delete('/sales/id={id}/delete-sales', 'deletedSales'); // Delete the Session in Sales
    });

    Route::controller(CreditInfoController::class)->group(function (){

        Route::get("/credit_info/index", 'index');
        Route::post("/credit_info" , 'storeCreditInfo');
        Route::get("/credit_info", 'showCreditInfo');
        Route::get("/credit_info/id={id}" ,"showById"); 
        Route::get('/credit_info/search/{credit_inform}', 'searchCredit_Info');
        Route::get("/credit_info/{id}/all_credit_info/", "showSoftDeleteCredit");
        Route::put("/credit_info/id={credit_info}/update" ,"updateCreditInfo");
        Route::delete("/credit_info/id={credit_info}/delete" ,"destroyCreditInfo");
        Route::delete("/credit_info/id={credit_info}/softdelete" ,"softdeleterecord");

    });


    Route::controller(CreditNamesController::class)->group(function (){

        Route::get("/credit_names/index", 'index');
        Route::post("/credit_names" , 'storeCreditNameInfo');
        Route::get('/credit_names/search/{credit_name}', 'searchCredit_Name');
        Route::get("/credit_names", 'showCreditName');
        Route::get("/credit_names/id={id}" ,"showById"); 
        Route::get("/credit_names/{id}/all_credit_name/", "showSoftDeletedCreditName");
        Route::put("/credit_names/id={credit_name}/update" ,"updateProduct");
        Route::delete("/credit_names/id={credit_name}/delete" ,"destroyCreditName");
        Route::delete("/credit_names/id={credit_name}/softdelete" ,"softdeleterecord");
    });

    });