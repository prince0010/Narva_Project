<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prod_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $prodtype_query = Prod_Types::query();

        if ($request->keyword) {
            $prodtype_query->where('product_type_name', 'LIKE', '%' . $request->keyword . '%');
        }

        $prod_type = $prodtype_query->paginate(10);

        if ($prod_type->count() > 0) {
            $Product_Types = $prod_type->map(function ($product_type) {
                return [
                    'id' => $product_type->id,
                    'product_type_name' => $product_type->product_type_name
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
            'product_type_name' => 'required|string|max:255'
        ]);

        if ($product_type->fails()) {
            return response()->json([
                'message' => $product_type->messages()
            ]);
        } else {
            $product_types = Prod_Types::create($request->all());

            return response()->json([
                'message' => 'Added the Product Name Successfully',
                "product_type" => [
                    "id" => $product_types->id,
                    "product_type_name" => $product_types->product_type_name,
                    "created_at" => $product_types->created_at,
                ],
            ]);
        }
    }

    public function showById($id){
        
        $product_type = Prod_Types::find($id);

        if($product_type){
            $producttypeData = [
                'id' => $product_type->id,
                // 'prod_type' => $product->prod_type,
                'product_type_name' => $product_type->product_type_name, //Specifying to show only the Product Type Name
                
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'products' => $producttypeData,
            ]);
        }
      
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

   
    public function showProductType(Prod_Types $product_Type)
    {
        $prodtype_que = $product_Type->paginate(10);

        if($prodtype_que -> count() > 0){
            $ProductsTypeData = $prodtype_que->map(function ($producttype) {
                return [
                    'id' => $producttype->id,
                    // 'prod_type' => $product->prod_type,
                    'product_type_name' => $producttype->product_type_name, //Specifying to show only the Product Type Name
                     
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $ProductsTypeData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showSoftDeletedProductType($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedProductTypes = Prod_Types::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedProductTypes)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Product Type Data Found",
                    "product_type" => $softDeletedProductTypes
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Product Type Data Found",
                ]);
            }
        } else {
            if ($id == 0) {
                // Display the non-deleted records
                $activeProductTypes = Prod_Types::all()->toArray();
                if (!empty($activeProductTypes)) {
                    return response()->json([
                        "status" => "200",
                        "message" => "Active Product Type Data Found",
                        "product_type" => $activeProductTypes
                    ]);
                } else {
                    return response()->json([
                        "status" => "404",
                        "message" => "No Active Product Type Data Found",
                    ]);
                }
            }
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateProductType(Request $request, Prod_Types $product_Type)
    {

        $request->validate([
            'product_type_name' => 'required|string|max:255',
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
    public function searchProductType($product_name)
    {

        $prod_t = Prod_Types::where('product_type_name', 'like', '%' . $product_name . '%')->get();

        if (empty(trim($product_name))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
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

    // Soft Delete
    public function softdeleterecord($product_Type)
    {

        $data = Prod_Types::find($product_Type);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Product Type not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Product Type Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }
}
