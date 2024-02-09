<?php

namespace App\Http\Controllers;

use App\Models\Product_Type;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function storeProductType(Request $request)
    {
        $request-> validate([
            'product_name' => 'required|string|max:255',
        ]);

        $product_type = Product_Type::create($request->all());

        if($product_type){
            return response()->json([
                "status" => 200,
                "message" => "Added the Product Name Successfully",
                "data" => $product_type,
            ]);
        }
        else{
            return response()->json([
               
                "status" => 401,
                "message" => "Failed to Add a Product Name",
            ]);   
        }
    }

    /**
     * Display the specified resource.
     */
    public function showProductType(Product_Type $product_Type)
    {
        // return view('products.show', compact('products'));
        if($product_Type){
            return response()->json([
                "status" => "200",
                "message" => "There are Product Data Found",
                "data" => $product_Type
            ]);
        }
        else{
            return response()->json([
                "status" => "401",
                "message" => "The Product Data is not Existed"
            ]);
        }
    }
    public function showAllProductType(Product_Type $product_Type)
    {
        // return view('products.show', compact('products'));
        $product_Type = Product_Type::all()->toArray();
        if($product_Type){
            return response()->json(
                [
                    "status" => "200",
                    "message" => "There are Products Data Found",
                 "Products" => $product_Type
                 ]
        );
        }
        else{
            return response()->json(
                [
                    "status" => "401",
                    "message" => "The Products Data is Not Existed",
                 ]
        );
        }
        
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateProductType(Request $request, Product_Type $product_Type)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProductType(Product_Type $product_Type)
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
