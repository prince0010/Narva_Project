<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\CreditInfoController;
use App\Http\Controllers\CreditInformController;
use App\Http\Controllers\CreditNamesController;
use App\Http\Controllers\CreditUsersController;
use App\Http\Controllers\DownpaymentInfoController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\MarkupController;
use App\Http\Controllers\ProdTypesController ;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuppliesController;
use App\Http\Controllers\TransactionDetailsController;
use App\Http\Controllers\TransactionDetailsLogsController;
use App\Models\TransactionDetailsLog;

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
        // Route::get("/products/import-form", "import");
        Route::post("/products/import" , "import");
        Route::post("/products/add-quantites", "addQuantities");
        Route::post('/products/id={productId}/stock/', 'addStockbyID'); 
        // Route::get('/products/{productTypeId}/filter', 'getProductsByProductType');
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
        // Route::get("/supplier/import-form", "import");
        Route::post("/supplier/import" , "import");
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

   
    Route::controller(CreditUsersController::class)->group(function (){

        Route::get("/credit_users/index", "index");
        Route::post("/credit_users", "storeCreditUsers");
        Route::get("/credit_users/id={id}" ,"showById");
        Route::get("/credit_users", "showCreditUsers");
        Route::get("/credit_users/{id}/all_credit_users/", "showSoftDeletedCreditUser"); // Show Soft Deleted and Non Deleted Products Type || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::put("/credit_users/id={credit_users}/update", "updateCreditUser");
        Route::get('/credit_users/search/{credit_user}', 'searchCreditUser');
        Route::delete("/credit_users/id={credit_user}/delete", "destroyCreditUser"); 
        Route::delete("/credit_users/id={credit_user}/softdelete", "softdeleterecord");
        
    });

    Route::controller(DownpaymentInfoController::class)->group(function (){

        Route::get("/downpayment/index", "index");
        Route::post("/downpayment/add_downpayment", "addDownpayment");
        Route::get("/downpayment/id={id}" ,"showById");
        Route::get("/downpayment", "showDownpayment");
        Route::get("/downpayment/{id}/all_downpayment/", "showSoftDeletedDownpayment"); // Show Soft Deleted and Non Deleted Products Type || 1 = Soft Deleted | 0 = Not Soft Deleted
        Route::get('/downpayment/search/{downpayment}', 'searchDownpayment');
        Route::delete("/downpayment/id={downpayment}/delete", "destroyDownpayment"); 
        Route::delete("/downpayment/id={downpayment}/softdelete", "softdeleterecord");
        Route::put("/downpayment/{id}/update_downpayment", "updateDownpayment");
    
    });

    Route::controller(CreditInformController::class)->group(function (){

        Route::get("/credit_information/index", "index");
        Route::post("/credit_information", "storeCreditInform");
        Route::get("/credit_information/id={id}" ,"showById");
        Route::get("/credit_information", "showCreditInform");
        Route::get("/credit_information/{id}/all_credit_information/", "showSoftDeletedCredInform");
        Route::put("/credit_information/id={credit_inform}/update", "updateCreditInform");
        Route::delete("/credit_information/{credit_name}/delete_user", "deleteCreditInformByCreditName"); //Delete the Records of the records sa nag credit and its record sa credits_user database as well
        Route::delete("/credit_information/id={credit_inform}/softdelete", "softdeleterecord");
        Route::delete("/credit_information/{id}/delete", "deleteCredInform");
        
   
    });

    Route::controller(TransactionDetailsController::class)->group(function (){
        Route::get("/transaction_details/index", "index");

        Route::get("/credit-users/{credit_users_id}/credit-and-downpayment-info", "getCreditAndDownpaymentInfo"); //Find the Record of total_charnge, total_downpayment, balance and status by input the credit_user_id

        Route::get("/transaction_details/{credit_name}" ,"showByCreditName");
        Route::get("/transaction_details/{id}/all_credit_information/", "showSoftDeletedTransactionDetails");
        Route::delete("/transaction_details/id={transaction_details}/softdelete", "softdeleterecord");
    });

  
    });