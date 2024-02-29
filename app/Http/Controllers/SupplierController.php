<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index(Request $request)
    {

        $supplier_query = Supplier::query();

        if ($request->keyword) {
            $supplier_query->where('supplier_name', 'LIKE', '%' . $request->keyword . '%');
        }

        $suppliers = $supplier_query->paginate(10);

        if ($suppliers->count() > 0) {
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
        $request->validate([
            'supplier_name' => 'required|string|max:255'
        ]);

        $supplier = Supplier::create($request->all());

        if ($supplier) {
            return response()->json([
                "status" => '200',
                "supppliers" => [
                    "id" => $supplier->id,
                    "supplier_name" => $supplier->supplier_name,
                    "created_at" => $supplier->created_at
                ],
                "message" => "supplier name added successfully",
            ]);
        } else {
            return response()->json([
                "status" => '401',
                "message" => "There is something wrong in Adding Supplier Name"
            ]);
        }
    }

    public function showById($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $SupplierData = [
                'id' => $supplier->id,
                'supplier_name' => $supplier->supplier_name, //Specifying to show only the Supplier Name
                
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'suppliers' => $SupplierData,
            ]);
        }
      
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }

    
    public function showSupplier(Supplier $suppliers)
    {
        $supplier_que = $suppliers->paginate(10);

        if($supplier_que -> count() > 0){
            $SuppliersData = $supplier_que->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'supplier_name' => $supplier->supplier_name, 
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'suppliers' => $SuppliersData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showSoftDeletedSupplier($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedSupplier = Supplier::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedSupplier)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Supplier Data Found",
                    "suppliers" => $softDeletedSupplier
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Supplier Data Found",
                ]);
            }
        } else {
                // Display the non-deleted records
                if ($id == 0) {
                    $activeSupplier = Supplier::all()->toArray();
                    if (!empty($activeSupplier)) {
                        return response()->json([
                            "status" => "200",
                            "message" => "Active Supplier Data Found",
                            "suppliers" => $activeSupplier
                        ]);
                    } else {
                        return response()->json([
                            "status" => "404",
                            "message" => "No Active Supplier Data Found",
                        ]);
                    }
                }
            }
    }

    // Search API
    public function searchSupplier($supplier_name)
    {
        $supp = Supplier::where('supplier_name', 'like', '%' . $supplier_name . '%')->get();

        if (empty(trim($supplier_name))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
            return response()->json($supp);
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
            'supplier_name' => 'required|string|max:255'
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

    // Soft Delete
    public function softdeleterecord($supplier)
    {

        $data = Supplier::find($supplier);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Supplier not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Supplier Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }
}
