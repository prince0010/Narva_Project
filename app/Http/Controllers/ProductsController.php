<?php

namespace App\Http\Controllers;
use App\Imports\BaseImport;
use App\Imports\ProductsImport;
use App\Models\Prod_Types;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{

    public function index(Request $request)
    {
        $products_query = Products::with(['prod_type', 'supplier'])
            ->when($request->has('filter'), function ($query) use ($request) {
                $productTypeIds = explode(',', $request->filter);
                return $query->whereIn('prod_type_ID', $productTypeIds);
            })
            ->when($request->has('keyword'), function ($query) use ($request) {
                $keyword = '%' . $request->keyword . '%';
                return $query->where(function ($innerQuery) use ($keyword) {
                    $innerQuery->where('part_num', 'LIKE', $keyword)
                        ->orWhere('part_name', 'LIKE', $keyword)
                        ->orWhere('brand', 'LIKE', $keyword)
                        ->orWhere('model', 'LIKE', $keyword)
                        ->orWhere('price_code', 'LIKE', $keyword)
                        ->orWhere('stock', 'LIKE', $keyword);
                });
            })
            ->orderBy('id', 'asc'); 
    
        $products = $products_query->paginate(10);
    
        if ($products->count() > 0) {
            $ProductsData = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'prod_type' => $product->prod_type ? $product->prod_type->product_type_name : null,
                    'supplier' => $product->supplier ? $product->supplier->supplier_name : null,
                    'part_num' => $product->part_num,
                    'part_name' => $product->part_name,
                    'brand' => $product->brand,
                    'model' => $product->model,
                    'price_code' => $product->price_code,
                    'stock' => $product->stock
                ];
            });
    
            return response()->json([
                'status' => 200,
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
                'status' => 401,
                'message' => 'Products is empty'
            ]);
        }
    }
    //  Search
    public function searchProducts($products)
    {
        $prod = Products::where('part_name', 'like', '%' . $products . '%')
            ->orWhere('supplier_ID', 'like', '%' . $products . '%')->get();

        if (empty(trim($products))) {
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
        $request->validate([
            'prod_type_ID' => 'required|integer|digits_between:1,999',
            'supplier_ID' => 'required|integer|digits_between:1,999',
            'part_num' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price_code' => 'required|numeric', 
            'stock' => 'required|integer|digits_between:1,999',
            'markup' => 'nullable|numeric|min:0|max:100', 
        ]);
    
        // Convert price_code to characters from ORGANIZEDB
        $convertedPriceCode = $this->convertToOrganizedB($request->input('price_code'));
    
        // Calculate ang counter_price using markup (if provided)
        $counterPrice = $request->input('markup') ? $this->calculateCounterPrice($request->input('price_code'), $request->input('markup')) : null;
    
        $request->merge([
            'price_code' => $convertedPriceCode,
            'counter_price' => $counterPrice,
        ]);
    
        $product = Products::create($request->all());
    
        if ($product) {
            return response()->json([
                'status' => 200,
                'products' => $this->getProductResponseData($product, $convertedPriceCode),
                'message' => 'Added the Product Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to Add a Product',
            ]);
        }
    }
    private function convertToOrganizedB($priceCode)
    {
        $organizedB = 'ORGANIZEDB';
        $convertedPriceCode = '';
    
        // Convert each digit in price_code to the corresponding character in ORGANIZEDB
        foreach (str_split($priceCode) as $digit) {
            $convertedPriceCode .= $organizedB[$digit - 1]; // Subtract 1 para ma adjust for the array indexing
        }
    
        return $convertedPriceCode;
    }
    private function calculateCounterPrice($priceCode, $markup)
    {
        $price = floatval($priceCode);
        $markup = $markup / 100; // Convert percentage to decimal
        return $price * (1 + $markup);
    }
    
    // Helper function to get the response data for a product
    private function getProductResponseData($product, $convertedPriceCode)
    {
        return [
            'id' => $product->id,
            'prod_type' => $product->prod_type,
            'supplier' => $product->supplier,
            'part_num' => $product->part_num,
            'part_name' => $product->part_name,
            'brand' => $product->brand,
            'model' => $product->model,
            'price_code' => $convertedPriceCode, // Use the converted price code for the response
            'stock' => $product->stock,
            // 'markup' => $product->markup,
            // 'counter_price' => $this->convertToOrganizedB($product->counter_price), // Convert counter_price to letters sa ORGANIZEDB
            'counter_price' => $product->counter_price,
        ];
    }

    // Filtering
    // public function getProductsByProductType($productTypeId)
    // {
    //     // Validate that $productTypeId is a positive integer
    //     if (!ctype_digit($productTypeId) || $productTypeId <= 0) {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Invalid product type ID provided.',
    //         ]);
    //     }

    //     // Cast $productTypeId to integer
    //     $productTypeId = (int)$productTypeId;

    //     // Retrieve the product type along with its associated products using eager loading
    //     $productType = Prod_Types::with('product')->find($productTypeId);

    //     if (!$productType) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Product type not found.',
    //         ]);
    //     }

    //     // Return the product type and its associated products
    //     return response()->json([
    //         'status' => 200,
    //         'product_type' => $productType,
    //         'message' => 'Products retrieved successfully for the specified product type.',
    //     ]);
    // }

    public function showById($id)
    {

        $product = Products::with('prod_type')->find($id);

        if ($product) {
            $productData = [
                'id' => $product->id,
                // 'prod_type' => $product->prod_type,
                'prod_type' => $product->prod_type, //Specifying to show only the Product Type Name
                "supplier" => $product->supplier,
                'part_num' => $product->part_num,
                'part_name' => $product->part_name,
                'brand' => $product->brand,
                'model' => $product->model,
                'price_code' => $product->price_code,
                'stock' => $product->stock
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $productData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }
    

    public function showProduct(Products $products)
    {

        $prod_que = $products->get();

        if ($prod_que->count() > 0) {
            $ProductsData = $prod_que->map(function ($product) {
                return [
                    'id' => $product->id,
                    'prod_type' => $product->prod_type,
                    "supplier" => $product->supplier,
                    'part_num' => $product->part_num,
                    'part_name' => $product->part_name,
                    'brand' => $product->brand,
                    'model' => $product->model,
                    'price_code' => $product->price_code,
                    'stock' => $product->stock
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
            'prod_type_ID' => 'required|integer|digits_between:1, 999',
            'supplier_ID' => 'required|integer|digits_between:1, 999',
            'part_num' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price_code' => 'required|string|max:255', // In Specific Code there is a price on it so example in RRNB the price of it is Pesos 3150.00
            'stock' => 'required|integer|digits_between: 1, 999',
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
    public function softdeleterecord($products)
    {

        $data = Products::find($products);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Products not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Products Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }
    // Adding the Quantity of Stock
    public function addStock(Request $request, $products)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Products::find($products);

        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found'
            ]);
        }

        // temporary input 
        $quantity = (int) $request->input('quantity');

        if ($product->addStock($quantity)) {
            return response()->json([
                'status' => '201',
                'message' => 'Stock Added Successfully',
                'stock-left' => $product->stock,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to Add the Stock',
            ]);
        }
    }

    // Display the Top 10 Lowest Stock

    public function lowestStock()
    {
        $perPage = 10; // You can adjust this based on your preferences
    
        $lowestStockProducts = Products::select(
            'products.id',
            'products.supplier_ID',
            'suppliers.supplier_name',
            'products.part_num',
            'products.part_name',
            'products.brand',
            'products.model',
            'products.price_code',
            'products.stock',
        )
            ->leftJoin('suppliers', 'products.supplier_ID', '=', 'suppliers.id')
            ->orderBy('products.stock')
            ->paginate($perPage);
    
        $lowStockProductsData = $lowestStockProducts->filter(function ($product) {
            return $product->stock == 0;
        })->map(function ($product) {
            $supplierName = $product->supplier ? $product->supplier->supplier_name : null;
            return [
                'products_id' => $product->id,
                'supplier_name' => $supplierName,
                'part_num' => $product->part_num,
                'part_name' => $product->part_name,
                'brand' => $product->brand,
                'model' => $product->model,
                'price_code' => $product->price_code,
                'stock-left' => $product->stock,
            ];
        });
    
        if ($lowStockProductsData->count() > 0) {
            return response()->json([
                'status' => 200,
                'message' => 'Products with Low Stock',
                'stocks_data' => $lowStockProductsData,
                'pagination' => [
                    'total' => $lowestStockProducts->total(),
                    'per_page' => $lowestStockProducts->perPage(),
                    'current_page' => $lowestStockProducts->currentPage(),
                    'last_page' => $lowestStockProducts->lastPage(),
                ],
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'No Products with Low Stock Available',
            ]);
        }
    }
    // Out Of Stock
    public function outofStock()
    {

        $outOfStockProducts = Products::where('stock', 0)
            ->leftJoin('suppliers', 'products.supplier_ID', '=', 'suppliers.id')
            ->select([
                'products.id',
                'products.supplier_ID',
                'suppliers.supplier_name',
                'products.part_num',
                'products.part_name',
                'products.brand',
                'products.model',
                'products.price_code',
                'products.stock',
            ])
            ->orderByDesc('products.id') //Desc Order
            ->get();

        $formattedProducts = [];

        foreach ($outOfStockProducts as $product) {
            $supplierName = $product->supplier ? $product->supplier->supplier_name : null;

            $formattedProducts[] = [
                'products_id' => $product->id,
                'supplier_name' => $supplierName,
                'part_num' => $product->part_num,
                'part_name' => $product->part_name,
                'brand' => $product->brand,
                'model' => $product->model,
                'price_code' => $product->price_code,
                'stock' => $product->stock,
            ];
        }

        return response()->json(['product' => $formattedProducts]);
    }
   
    public function import(Request $request)
    {
        try {
            $filePath = $request->file('file')->getRealPath();

            // Load the Excel file using Maatwebsite\Excel
            $import = new ProductsImport();
            Excel::import($import, $filePath);

            return response()->json([
                'status' => 200,
                'message' => 'Import successful',
            ]);
        } catch (\Exception $e) {
            Log::error("Error during import: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Add Quantities if out of stock
    public function addQuantities(Request $request)
    {
        $data = $request->input('data', []);

        foreach ($data as $item) {
            $productId = $item['product_id'] ?? null;
            $quantity = $item['quantity'] ?? 0;

            if (!$productId || $quantity <= 0) {
                // If Invalid ang Data then mu skip siya sa next iteration
                continue;
            }

            $product = Products::findOrFail($productId);
            $product->addStock($quantity);
      
                // Add the details of the product to the response array
        $prod_que[] = [
            'product_id' => $product->id,
            'part_num' => $product->part_num,
            'brand' => $product->brand,
            'model' => $product->model,
            'quantity_added' => $quantity,
            'stock' => $product->stock,
        ];
        }

        return response()->json([
            'status'=> 200,
            'message' => 'Quantities Added Successfully',
            'data' => $prod_que
        ]);
    }
    public function addStockbyID(Request $request, $productId){
        $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        try {
            $product = Products::findOrFail($productId);

            // Check if the current stock is 0
            if ($product->stock == 0) {
                $quantity = $request->input('quantity');
                
                $product->addStock($quantity);

                $prod_que[] = [
                        "product_id" => $product->id,
                        "prod_type" => $product->prod_type,
                        "supplier" => $product->supplier,
                        "part_num" => $product->part_num,
                        "part_name" => $product->part_name,
                        "brand" => $product->brand,
                        "model" => $product->model,
                        "price_code" => $product->price_code,
                        "stock" => $product->stock
                ];

                return response()->json([
                    'status' => 200,
                    'message' => 'Stock Added Successfully',
                    'product' => $prod_que,
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'This Product still have Stock in it.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to add stock',
                'error' => $e->getMessage(),
            ]);
        }
    }
    }