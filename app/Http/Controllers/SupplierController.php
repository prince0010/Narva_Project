<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index(Request $request){
        
        $supplier_query = Supplier::query();
       
           if ( $request->keyword ) {
            $supplier_query->where('supplier_name', 'LIKE', '%' .$request->keyword.'%');
        }

        $suppliers = $supplier_query->paginate(10);

        if($suppliers -> count() > 0){
            $SuppliersData = $suppliers->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'supplier_name' => $supplier->supplier_name
                ];
            });
    
            return response()->json([
                'status' => '200',
                'message' => 'successfully added supplier',
                'suppliers' => $SuppliersData,
                'pagination' => [
                    'current_page' => $suppliers->currentPage(),
                    'total' => $suppliers->total(),
                    'per_page' => $suppliers->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Supplier is empty'
            ]);
        }

     }

    /**
     * Store a newly created resource in storage.
     */
    public function addSupplier(Request $request)
    {
        $request -> validate([
            'supplier_name' =>'required|string|max:255'
        ]);

        $supplier = Supplier::create($request->all());

        if($supplier)
        {
            return response()->json([
                "status" => '200',
                "supppliers" =>[
                    "id" => $supplier->id,
                    "supplier_name" => $supplier->supplier_name,
                    "created_at" => $supplier->created_at
                ],
                    "message" => "supplier name added successfully",
                ]);
        }
        else
        {
            return response()->json([
                "status" => '401',
                "message" => "There is something wrong in Adding Supplier Name"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showSupplier(Supplier $supplier)
    {
        if($supplier){
            return response()->json(
              [ 
                  "message" => "Found the Specific User",
                  "status" => "200",
                  "data" => $supplier
              ]
            );
        }
        elseif($supplier == NULL){
            return response()->json([
                "status" => "500",
                "message" => "No Data Is Existed",
                "Data" => $supplier
            ]);

        }
      
    }

    public function showAllSupplier()
    {
        $supplier = Supplier::all()->toArray();
        if($supplier){
            return response()->json(
              [ 
                "data" => $supplier,
                "status" => "200"
              ]
            );
        }
        elseif($supplier == NULL){
            return response()->json([
                "status" => "500",
                "message" => "No Data Is Existed",
                "Data" => $supplier
            ]);

        }
      
    }
    /**
     * Show the form for editing the specified resource.
     */
  
    /**
     * Update the specified resource in storage.
     */
    public function updateSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'supplier_name' =>'required|string|max:255'
        ]);

        if ($supplier->update($request->all())) {
          
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Supplier Name Successfully",
                "data" => $supplier,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Supplier Name",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteSupplier(Supplier $supplier)
    {
        if ($supplier->delete()) {
       
            return response()->json([
                "Data" =>  $supplier,
                "status" => 200,
                "message" => "You Deleted the Supplier Name Successfully",
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Supplier Name",
            ]);
        }
    }

    
      /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $product = Supplier::latest()->paginate(5);


    //     return view('supplier.index', compact('supplier'))
    //         ->with('i', (request()->input('page', 1) - 1) * 10);
    //     // llike forloop
    // }

    /**
     * Show the form for creating a new resource.
     */
    // public function createSupplier() : View
    // {
    //     //  supplier.create the supplier here is a resource route for the web.php
    //     return view('supplier.createSupplier');
    // }

    // public function editSupplier(Supplier $supplier): View
    // {
    //     // supplier.edit the supplier here is a resource route for the web.php
    //     return view('supplier.editSupplier');
    // }
}
