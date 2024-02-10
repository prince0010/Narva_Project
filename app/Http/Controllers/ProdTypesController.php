<?php

namespace App\Http\Controllers;

use App\Models\Prod_Types;
use Illuminate\Http\Request;

class ProdTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function storeProductType(Request $request)
    {
        $request-> validate([
            'product_name' => 'required|string|max:255'
        ]);

        $product_type = Prod_Types::create($request->all());

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
    public function showProductType(Prod_Types $product_Type)
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
    public function showAllProductType(Prod_Types $product_Type)
    {
        // return view('products.show', compact('products'));
        $product_Type = Prod_Types::all()->toArray();
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
