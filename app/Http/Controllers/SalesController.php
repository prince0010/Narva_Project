<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $salesData = sales::select('sales.*') 
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
                    "sale_id" => $sale->id,
                    "product" => [
                        "product_id" => $sale->product->id,
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
        $request->validate([
            'product_id' => 'required|integer|digits_between:1, 999',
            'markup_id' => 'required|integer|digits_between:1, 999',
            'quantity' => 'required|integer|digits_between:1,100',
            'total' => 'required|numeric|between:0,99999.99',
            'sale_date' => 'required|date|date_format:Y-m-d',
            'remarks' => 'required|string|max:255',
        ]);

        // Find the Product ID Based sa gi input na product_ID
        $product = Products::find($request->input('product_id'));
    
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found'
            ]);
        }
    
        $quantity = (int)$request->input('quantity');
    
        // Checking if naa bay enough stock
        if ($product->stock < $quantity) {
            return response()->json([
                'status' => 422,
                'message' => 'Insufficient stock',
            ]);
        }
    
        $sales = sales::create($request->all());
    
        if ($sales) {
            // Subtract the stock from the product
            if ($product->subtractStock($quantity)) {
                return response()->json([

                    'status' => 201,
                    'message' => 'Sales Added Successfully',
                    'sales' => [
                        'sale_id' => $sales->id,
                        'stock-left' => [
                            'id' => $product->id,
                            'stock' => $product->stock,
                        ],
                        'markup' => $sales->markup,
                        'quantity' => $sales->quantity,
                        'total' => $sales->total,
                        'sale_date' => $sales->sale_date,
                        'remarks' => $sales->remarks
                    ],
                ]);

            } else {
                // If deducting the stock fails then I rollback ang sales entry
                // Delete the Created and Request Sales Input
                $sales->delete();
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to Deduct the Stock',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to Add the Sales',
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

        $sales_que = $sales->get();

        if ($sales_que->count() > 0) {
            $SalesData = $sales_que->map(function ($sale) {
                return [
                    "id" => $sale->id,
                    "product" => [
                        "product_id" => $sale-> product -> id,
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
                    "product_id" => $sale-> product -> id,

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

    // Get the Monthly and Yearly Reports
    public function getTopProducts($yearly = null, $monthly = null)
    {
        $query = Products::select(
                'products.id',
                DB::raw('MAX(products.part_num) as part_num'),
                DB::raw('MAX(products.part_name) as part_name'),
                DB::raw('MAX(products.brand) as brand'),
                DB::raw('MAX(products.model) as model'),
                DB::raw('MAX(products.price_code) as price_code'),
                DB::raw('MAX(products.stock) as stock'),
                DB::raw('SUM(sales.quantity) as total_quantity')
            )
            ->join('sales', 'products.id', '=', 'sales.product_id')
            ->whereYear('sales.sale_date', $yearly);

        if($monthly) {
            $query->whereMonth('sales.sale_date', $monthly);
        }

        $topProducts = $query
            ->groupBy('products.id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        if($topProducts->count() > 0) {
            $topProductsData = $topProducts->map(function ($product) {
                // Get an array of Sales ID
                $salesID = $product->sales->pluck('id');
                return [
                    'sales_id' => $salesID,
                    'product_id' => $product->id,
                    'part_num' => $product->part_num,
                    'part_name' => $product->part_name,
                    'brand' => $product->brand,
                    'model' => $product->model,
                    'price_code' => $product->price_code,
                    'total_quantity' => $product->total_quantity,
                ];
            });

            return response()->json([
                'status' => '200',
                'message' => 'Top 10 Products with Highest Total Quantity in Sales',
                'products' => $topProductsData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'No Sales Data Available',
            ]);
        }
    }

    // Deleted Sales == the quantity mubalik sa Products na quantity sa orginal quantity
    public function deletedSales($id){
        $sales = sales::find($id);

        if (!$sales) {
            return response()->json([
                'status' => 404,
                'message' => 'Sales Not Found',
            ]);
        }

        $product = $sales->product;

        if ($sales->delete()) {
            // Add the stock back to the product
            $product->addStock($sales->quantity);

            return response()->json([
                'status' => 200,
                'message' => 'Sales Deleted Successfully',
                'sale-deleted' => [
                    'product_id' => $product->id,
                    'part_name' => $product->part_name,
                    'stock-left' => $product->stock // Displaying the Stock Left
                ]
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to Delete Sales',
            ]);
        }
    }
    }
