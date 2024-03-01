<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    // public function index(Request $request){

    //     $products_query = Products::query();

    //     if($request)
    // }

    public function index(Request $request){
        
        $products_query = Products::query();
       $req = $request->keyword;
           if ($req) {
            $products_query->where('part_num', 'LIKE', '%' .$req.'%')
            ->orWhere('part_name', 'LIKE', '%' .$req.'%')
            ->orWhere('brand', 'LIKE', '%' .$req.'%')
            ->orWhere('model', 'LIKE', '%' .$req.'%')
            ->orWhere('price_code', 'LIKE', '%' .$req.'%')
            ->orWhere('stock', 'LIKE', '%' .$req. '%');
        }

        $products = $products_query->paginate(10);

        if($products -> count() > 0){
            $ProductsData = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    // 'prod_type' => $product->prod_type,
                    'prod_type' => $product->prod_type->product_type_name, //Specifying to show only the Product Type Name
                    'supplier_type'=>$product->supplier->supplier_name,
                    'part_num' => $product->part_num,
                    'part_name'=> $product->part_name,
                    'brand' => $product->brand,
                    'model' =>$product->model,
                    'price_code' =>$product->price_code,
                    'stock' => $product->stock
                ];
            });

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $ProductsData,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Products is empty'
            ]);
        }

     }

    //  Search
     public function searchProducts($products){
        $prod = Products::where('part_name', 'like', '%'.$products.'%')
                        ->orWhere('supplier_ID', 'like', '%'.$products.'%')->get();
 
        if(empty(trim($products))) {
         return response()->json([
             "status" => "204",
             "message" => "No Input is Provided for Search",
         ]);
     } else {
         return response()->json($prod);
     }
     }


    public function storeProduct(Request $request, Products $products)
    {
        //
        $request->validate([
            'prod_type_ID' => 'required|integer|digits_between:1, 999',
            'supplier_ID' => 'required|integer|digits_between:1, 999',
            'part_num' => 'required|string|max:255',
            'part_name'=> 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price_code' => 'required|string|max:255', // In Specific Code there is a price on it so example in RRNB the price of it is Pesos 3150.00
            'stock' => 'required|integer|digits_between: 1, 10',
        ]);
        $products = Products::create($request->all());
  
        if ($products) {
            return response()->json([
                    "status" => 200,
                    "products" => [
                        "id" => $products->id,
                        "prod_type" => $products->prod_type,
                        "supplier_type" => $products->supplier,
                        "part_num" => $products->part_num,
                        "part_name" => $products->part_name,
                        "brand" => $products->brand,
                        "model" => $products->model,
                        "price_code" => $products->price_code,
                        "stock"=>$products->stock
                    ],
                    // "pagination" => [
                    //     'current_page' => $products->currentPage(),
                    //     'total' => $products->total(),
                    //     'per_page' => $products->perPage(),
                    // ],
                  
                    "message" => "Added the Product Successfully",
                ]);
            // return redirect()->route('products.index')
            //     ->with(response()->json([
            //         'status' => 200,
            //         "message" => "Added the Product Successfully",
            //     ]));

            //    return response()->json([
            //         'status' => 200,
            //         "message" => "Added the Product Successfully",
            //     ]);
        } else {
            return response()->json([
               
                "status" => 401,
                "message" => "Failed to Add a Product",
            ]);
        }
    }

    
    public function showById($id){

        $product = Products::with('prod_type')->find($id);


        if($product){
            $productData = [
                'id' => $product->id,
                // 'prod_type' => $product->prod_type,
                'prod_type' => $product->prod_type, //Specifying to show only the Product Type Name
                "supplier_type" => $product->supplier,
                'part_num' => $product->part_num,
                'part_name'=> $product->part_name,
                'brand' => $product->brand,
                'model' =>$product->model,
                'price_code' =>$product->price_code,
                'stock'=>$product->stock
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'products' => $productData,
            ]);
        }
      
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showProduct(Products $products)
    {
        
        $prod_que = $products->paginate(10);

        if($prod_que -> count() > 0){
            $ProductsData = $prod_que->map(function ($product) {
                return [
                    'id' => $product->id,
                    // 'prod_type' => $product->prod_type,
                    'prod_type' => $product->prod_type->product_type_name, //Specifying to show only the Product Type Name
                    "supplier_type" => $product->supplier,
                    'part_num' => $product->part_num,
                    'part_name'=> $product->part_name,
                    'brand' => $product->brand,
                    'model' =>$product->model,
                    'price_code' =>$product->price_code,
                    'stock'=>$product->stock
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $ProductsData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showSoftDeletedProduct($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedProduct = Products::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedProduct)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Product Data Found",
                    "product" => $softDeletedProduct
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Product Data Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            $activeProduct = Products::all()->toArray();
            if (!empty($activeProduct)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Active Product Data Found",
                    "product" => $activeProduct
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Active Product Data Found",
                ]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // UPDATE = PUT
    // If you try to update in POSTMAN use the Body -> x-www-form-urlencoded to edit the user with its specific ID and you must put the table data's example here: product_name, ppproduct_details, quantity 
    // public function update(Request $request, Products $products) : RedirectResponse
    public function updateProduct(Request $request, Products $products)
    {
        $request->validate([
            'prod_type_ID' => 'required|integer|digits_between:1, 10',
            'supplier_ID' => 'required|integer|digits_between:1, 999',
            'part_num' => 'required|string|max:255',
            'part_name'=> 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price_code' => 'required|string|max:255', // In Specific Code there is a price on it so example in RRNB the price of it is Pesos 3150.00
            'stock' => 'required|integer|digits_between: 1, 10',
        ]);

        if ($products->update($request->all())) {
            // return redirect()->route('products.index')
            // ->with(response()->json([
            //     'status' => 200,
            //     "message" => "You Updated the Product Successfully",
            // ]));
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Product Successfully",
                "data" => $products,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Product",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // Variable Name sa Products kay $products
    // Delete
    // public function destroy(Products $products) : RedirectResponse
    public function destroyProduct(Products $products)
    {
        //
        if ($products->delete()) {
        
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Product Successfully",
                "data" => $products,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Product",
            ]);
        }
    }

      // Soft Delete
      public function softdeleterecord($products){

        $data = Products::find($products);

        if(!$data){
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Products not found',
                ]);
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Products Soft Deleted Successfully',
                'data' => $data
            ]);

    }

}
