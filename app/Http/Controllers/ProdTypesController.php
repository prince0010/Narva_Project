<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prod_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProdTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request){
        
        $prodtype_query = Prod_Types::query();
       
           if ( $request->keyword ) {
            $prodtype_query->where('product_name', 'LIKE', '%' .$request->keyword.'%');
        }

        $prod_type = $prodtype_query->paginate(10);

        if($prod_type -> count() > 0){
            $Product_Types = $prod_type->map(function ($product_type) {
                return [
                    'id' => $product_type->id,
                    'product_name' => $product_type->product_name
                ];
            });
    
            return response()->json([
                'status' => '200',
                'message' => 'successfully added product type',
                'product_types' => $Product_Types,
                'pagination' => [
                    'current_page' => $prod_type->currentPage(),
                    'total' => $prod_type->total(),
                    'per_page' => $prod_type->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'product type is empty'
            ]);
        }

     }


    public function storeProductType(Request $request)
    {
        // $request-> validate([
        //     'product_name' => 'required|string|max:255'
        // ]);

        

        // $product_type = Prod_Types::create($request->all());

        $product_type = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255'
        ]);

        if($product_type -> fails()){
            return response()->json([
                'message' => $product_type->messages()
            ]);
        }else{
                   $product_types = Prod_Types::create($request->all());

            return response()->json([ 
                'message' => 'Added the Product Name Successfully',
                "product_type" => [
                                "id" => $product_types->id,
                                "product_name" => $product_types->product_name,
                                "created_at" => $product_types->created_at,
                            ],
            ]);
        }

        // if($product_type){
        //     return response()->json([                                                                        
        //         "status" => 200,
        //         "Product Type" => [
        //             "id" => $product_type->id,
        //             "product_name" => $product_type->product_name,
        //             "created_at" => $product_type->created_at,
        //         ],
        //         "message" => "Added the Product Name Successfully",
        //     ]);
        // }
        // else{
        //     return response()->json([
               
        //         "status" => 401,
        //         "message" => "Failed to Add a Product Name",
        //     ]);   
        // }
    }

    /**
     * Display the specified resource.
     */
    public function showProductType(Prod_Types $product_Type)
    {
        // return view('products.show', compact('products'));
        if($product_Type){
            return response()->json([
                "status" => "200",
                "message" => "There are Product Type Data Found",
                "data" => $product_Type
            ]);
        }
        else{
            return response()->json([
                "status" => "401",
                "message" => "The Product Type Data is not Existed"
            ]);
        }
    }
    public function showAllProductType()
    {
        // return view('products.show', compact('products'));
        $product_Type = Prod_Types::all()->toArray();
        if($product_Type){
            return response()->json(
                [
                    "status" => "200",
                    "message" => "There are Product Type Data Found",
                 "product_type" => $product_Type
                 ]
        );
        }
        else{
            return response()->json(
                [
                    "status" => "401",
                    "message" => "The Product Type Data is Not Existed",
                 ]
        );
        }
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function updateProductType(Request $request, Prod_Types $product_Type)
    {

        $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        if ($product_Type->update($request->all())) {
            // return redirect()->route('products.index')
            // ->with(response()->json([
            //     'status' => 200,
            //     "message" => "You Updated the Product Successfully",
            // ]));
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Product Data Successfully.",
                "data" => $product_Type,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Product Data.",
            ]);
        }
    }

    // Search API
    public function searchProductType($product_name){
        
        $prod_t = Prod_Types::where('product_name', 'like', '%'.$product_name.'%')->get();

        if(empty(trim($product_name))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } 

        // elseif($products->isEmpty()) {
        //     return response()->json([
        //         "status" => "404",
        //         "message" => "Cannot Found the Data",
        //     ]);
        // }
        else {
            return response()->json($prod_t);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProductType(Prod_Types $product_Type)
    {
        //
        if ($product_Type->delete()) {
            // return redirect()->route('products.index')
            // ->with(response()->json([
            //     "status" => 200,
            //     "message" => "Product Deleted Sucessfully",
            // ]));
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Product Data Successfully.",
                "data" => $product_Type,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Product Data.",
            ]);
        }
    }
}
