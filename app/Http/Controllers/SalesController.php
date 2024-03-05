<?php

namespace App\Http\Controllers;

use App\Models\sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{

    public function index(Request $request)
    {

        $sales_query = sales::query();
        
        $req = $request->keyword;
        if ($req) {
            $sales_query->where('quantity', 'LIKE', '%' . $req . '%')
                ->orWhere('total', 'LIKE', '%' . $req . '%')
                ->orWhere('sale_date', 'LIKE', '%' . $req . '%')
                ->orWhere('remarks', 'LIKE', '%' . $req . '%');
        }
        
         // Add order by sale_date in descending order
        $sales_query->orderBy('id', 'desc');
        $sales = $sales_query->paginate(10);

        if ($sales->count() > 0) {
            $SalesData = $sales->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'part_name' => $sale->product->part_name, 
                    'part_num' => $sale->product->part_num, 
                    'brand' =>  $sale->product->brand,
                    'model' => $sale->product->model,
                    'price_code' => $sale->product->price_code,
                    "supplier" => $sale->product->supplier->supplier_name,
                    // 'product_type' => $sale->product,
                    // 'markup' => $sale->markup,
                    "markup" => $sale->markup->markup_name,
                    'quantity' => $sale->quantity,
                    'total' => $sale->total,
                    'sale_date' => $sale->sale_date,
                    'remarks' => $sale->remarks
                ];
            });

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'sales' => $SalesData,
                'pagination' => [
                    'current_page' => $sales->currentPage(),
                    'total' => $sales->total(),
                    'per_page' => $sales->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Sales is empty'
            ]);
        }
    }
    //  Search
    public function searchSales($sales)
    {
        $salesData = Sales::select('sales.*') 
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->join('markup', 'sales.markup_id', '=', 'markup.id')
        ->where('products.part_name', 'like', '%' . $sales . '%')
        ->orWhere('markup.markup_name', 'like', '%' . $sales . '%')
        ->get();

        if (empty(trim($sales))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
            $response = [
                "status" => 200,
                "sales" => [],
            ];
    
            foreach ($salesData as $sale) {
                $response['sales'][] = [
                    "id" => $sale->id,
                    "product" => [
                        "id" => $sale->product->id,
                        "prod_type" => $sale->product->prod_type,
                        "part_num" => $sale->product->part_num,
                        "part_name" => $sale->product->part_name,
                        "brand" => $sale->product->brand,
                        "model" => $sale->product->model,
                        "price_code" => $sale->product->price_code,
                        "stock" => $sale->product->stock,
                        "created_at" => $sale->product->created_at,
                        "updated_at" => $sale->product->updated_at,
                        "deleted_at" => $sale->product->deleted_at,
                    ],
                    "markup" => $sale->markup,
                    "quantity" => $sale->quantity,
                    "total" => $sale->total,
                    "sale_date" => $sale->sale_date,
                    "remarks" => $sale->remarks,
                ];
            }
    
            return response()->json($response);
        }
    }

    public function storeSales(Request $request, sales $sales)
    {
        //
        $request->validate([
            'product_id' => 'required|integer|digits_between:1, 999',
            'markup_id' => 'required|integer|digits_between:1, 999',
            'quantity' => 'required|integer|digits_between:1,100',
            'total' => 'required|numeric|between:0,99999.99',
            'sale_date' => 'required|date|date_format:Y-m-d',
            'remarks' => 'required|string|max:255',
        ]);

        $sales = sales::create($request->all());

        if ($sales) {
            return response()->json([
                "status" => 200,
                "sales" => [
                    "id" => $sales->id,
                    "product" => [
                        "id" => $sales-> product -> id,

                        "prod_type" => $sales -> product -> prod_type,

                        "part_num" => $sales -> product -> part_num,
                        "part_name" => $sales-> product -> part_name,
                        "brand" => $sales -> product -> brand,
                        "model" => $sales -> product -> model,
                        "price_code" => $sales -> product -> price_code,
                        "stock"  => $sales -> product -> stock,
                        "created_at" => $sales -> product -> created_at,
                        "updated_at" => $sales -> product -> updated_at,
                        "deleted_at" => $sales -> product -> deleted_at,
                    ],
                    
                    "markup" => $sales-> markup,

                    "quantity" => $sales->quantity,
                    "total" => $sales->total,
                    "sale_date" => $sales->sale_date,
                    "remarks" => $sales->remarks
                ],
                "message" => "Added the Sales Successfully",
            ]);
        } else {
            return response()->json([

                "status" => 401,
                "message" => "Failed to Add a Sales",
            ]);
        }
    }


    public function showById($id)
    {

        $sales = sales::with(['product', 'markup'])->find($id);


        if ($sales) {
            $salesData = [
                'id' => $sales->id,
                "product" => [
                    "id" => $sales-> product -> id,

                    "prod_type" => $sales -> product -> prod_type,

                    "part_num" => $sales -> product -> part_num,
                    "part_name" => $sales-> product -> part_name,
                    "brand" => $sales -> product -> brand,
                    "model" => $sales -> product -> model,
                    "price_code" => $sales -> product -> price_code,
                    "stock"  => $sales -> product -> stock,
                    "created_at" => $sales -> product -> created_at,
                    "updated_at" => $sales -> product -> updated_at,
                    "deleted_at" => $sales -> product -> deleted_at,
                ],
                
                "markup" => $sales->markup,

                "quantity" => $sales->quantity,
                "total" => $sales->total,
                "sale_date" => $sales->sale_date,
                "remarks" => $sales->remarks
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'sales' => $salesData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }
    public function showSales(sales $sales)
    {

        $sales_que = $sales->paginate(10);

        if ($sales_que->count() > 0) {
            $SalesData = $sales_que->map(function ($sale) {
                return [
                    "id" => $sale->id,
                    "product" => [
                        "id" => $sale-> product -> id,

                        "prod_type" => $sale -> product -> prod_type,

                        "part_num" => $sale -> product -> part_num,
                        "part_name" => $sale-> product -> part_name,
                        "brand" => $sale -> product -> brand,
                        "model" => $sale -> product -> model,
                        "price_code" => $sale -> product -> price_code,
                        "stock"  => $sale -> product -> stock,
                        "created_at" => $sale -> product -> created_at,
                        "updated_at" => $sale -> product -> updated_at,
                        "deleted_at" => $sale -> product -> deleted_at,
                    ],
                    
                    "markup" => $sale->markup,

                    "quantity" => $sale->quantity,
                    "total" => $sale->total,
                    "sale_date" => $sale->sale_date,
                    "remarks" => $sale->remarks
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'sales' => $SalesData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }

    public function showSoftDeletedSales($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedSales = sales::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedSales)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Sales Data Found",
                    "sales" => $softDeletedSales
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Sales Data Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            $activeSales = sales::with(['product', 'markup'])->get();
            if (!empty($activeSales)) {
                $formattedSales = [];

                foreach ($activeSales as $sale) {
                    $formattedSale = [
                        "id" => $sale->id,
                        "product" => [
                            "id" => $sale->product->id,
                            "prod_type" => $sale -> product -> prod_type,
                            "part_num" => $sale->product->part_num,
                            "part_name" => $sale-> product -> part_name,
                            "brand" => $sale -> product -> brand,
                            "model" => $sale -> product -> model,
                            "price_code" => $sale -> product -> price_code,
                            "stock"  => $sale -> product -> stock,
                            "created_at" => $sale -> product -> created_at,
                            "updated_at" => $sale -> product -> updated_at,
                            "deleted_at" => $sale -> product -> deleted_at,
                        ],
                        "markup" => $sale->markup,
                        "quantity" => $sale->quantity,
                        "total" => $sale->total,
                        "sale_date" => $sale->sale_date,
                        "remarks" => $sale->remarks,
                    ];
            
                    $formattedSales[] = $formattedSale;
                }
            
                return response()->json([
                    "status" => "200",
                    "message" => "Active Sales Data Found",
                    "sales" => $formattedSales,
                ]);
            } else {
                return response()->json([
                    "status" => "204",
                    "message" => "No Active Sales Data Found",
                ]);
            }
    }
}

    public function updateSales(Request $request, sales $sales)
    {
        $request->validate([
            'product_id' => 'required|integer|digits_between:1, 999',
            'markup_id' => 'required|integer|digits_between:1, 999',
            'quantity' => 'required|integer|digits_between:1,100',
            'total' => 'required|numeric|between:0,99999.99',
            'sale_date' => 'required|date|date_format:Y-m-d',
            'remarks' => 'required|string|max:255',
        ]);

        if ($sales->update($request->all())) {

            return response()->json([
                'status' => 200,
                "message" => "You Updated the Sales Successfully",
                "data" => [
                    "id" => $sales->id,
                    "product" => [
                        "id" => $sales-> product -> id,

                        "prod_type" => $sales -> product -> prod_type,

                        "part_num" => $sales -> product -> part_num,
                        "part_name" => $sales-> product -> part_name,
                        "brand" => $sales -> product -> brand,
                        "model" => $sales -> product -> model,
                        "price_code" => $sales -> product -> price_code,
                        "stock"  => $sales -> product -> stock,
                        "created_at" => $sales -> product -> created_at,
                        "updated_at" => $sales -> product -> updated_at,
                        "deleted_at" => $sales -> product -> deleted_at,
                    ],
                    
                    "markup" => $sales->markup,

                    "quantity" => $sales->quantity,
                    "total" => $sales->total,
                    "sale_date" => $sales->sale_date,
                    "remarks" => $sales->remarks
                ],
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Sales",
            ]);
        }
    }

    public function destroySales(sales $sales)
    {
        //
        if ($sales->delete()) {

            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Sales Successfully",
                "data" => [
                    "id" => $sales->id,
                    "product" => [
                        "id" => $sales-> product -> id,

                        "prod_type" => $sales -> product -> prod_type,

                        "part_num" => $sales -> product -> part_num,
                        "part_name" => $sales-> product -> part_name,
                        "brand" => $sales -> product -> brand,
                        "model" => $sales -> product -> model,
                        "price_code" => $sales -> product -> price_code,
                        "stock"  => $sales -> product -> stock,
                        "created_at" => $sales -> product -> created_at,
                        "updated_at" => $sales -> product -> updated_at,
                        "deleted_at" => $sales -> product -> deleted_at,
                    ],
                    
                    "markup" => $sales->markup,

                    "quantity" => $sales->quantity,
                    "total" => $sales->total,
                    "sale_date" => $sales->sale_date,
                    "remarks" => $sales->remarks
                ],
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Sales",
            ]);
        }
    }
    // Soft Delete
    public function softdeleterecord($sales)
    {

        $sale  = sales::find($sales);

        if (!$sale ) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Sales not found',
                ]
            );
        }
        $sale ->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Sales Soft Deleted Successfully',
               "data" => [
                "id" => $sale->id,
                "product" => [
                    "id" => $sale-> product -> id,

                    "prod_type" => $sale -> product -> prod_type,

                    "part_num" => $sale -> product -> part_num,
                    "part_name" => $sale-> product -> part_name,
                    "brand" => $sale -> product -> brand,
                    "model" => $sale -> product -> model,
                    "price_code" => $sale -> product -> price_code,
                    "stock"  => $sale -> product -> stock,
                    "created_at" => $sale -> product -> created_at,
                    "updated_at" => $sale -> product -> updated_at,
                    "deleted_at" => $sale -> product -> deleted_at,
                ],
                
                "markup" => $sale->markup,

                "quantity" => $sale->quantity,
                "total" => $sale->total,
                "sale_date" => $sale->sale_date,
                "remarks" => $sale->remarks
                ],
            ]
        );
    }
}
