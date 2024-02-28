<?php

namespace App\Http\Controllers;

use App\Models\Supplies;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuppliesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */

     public function index(Request $request){
        
        $supplies_query = Supplies::query();
       $req = $request->keyword;
           if ($req) {
            $supplies_query->where('supplier_num', 'LIKE', '%' .$req.'%');
        }

        $supplies = $supplies_query->paginate(10);

        if($supplies -> count() > 0){
            $SuppliesData = $supplies->map(function ($supply) {
                return [
                    'id' => $supply->id,
                    'supplier' => $supply->supplier->supplier_name,
                    'products' => $supply->products,
                    'quantity'=> $supply->quantity,
                    'set' => $supply->set 
                ];
            });
    
            return response()->json([
                'status' => '200',
                'message' => 'successfully added supplies',
                'suppliers' => $SuppliesData,
                'pagination' => [
                    'current_page' => $supplies->currentPage(),
                    'total' => $supplies->total(),
                    'per_page' => $supplies->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Supplies is empty'
            ]);
        }

     }

    //  Search
     public function searchSupplies($id){
        $supp = Supplies::where('id', 'like', '%'.$id.'%')->get();
 
        if(empty(trim($id))) {
         return response()->json([
             "status" => "204",
             "message" => "No Input is Provided for Search",
         ]);
     } else {
         return response()->json($supp);
     }
     }


    public function addSupply(Request $request)
    {
        $request -> validate([
            // For digits length (Exact):
             'supplier_num' => 'required|integer|digits_between:1,10',
            // For String length (Exact): 'supplier_num' => 'required|integer|size:10',
            // For String Range : 'input' => 'required|string|min:5|max:10'
            // For digits Range : 'input' => 'required|digits_between:5,10'
            'products_ID' => 'required|integer|digits_between:1,10',
            'quantity' => 'required|integer|digits_between:1,8',
            'set' => 'required|string|max:255'
        ]);

        $supply = Supplies::create($request->all());
       
        if($supply){

            // $supply->save();

            return response()->json([
                'status' => '200',
                "supplies" => [
                    "id"=>$supply->id,
                    'supplier' => $supply->supplier,
                    'products' => $supply->products,
                    "quantity"=>$supply->quantity,
                    "set"=>$supply->set,
                ],
                'message' => 'You Successfully Add a Supply',
            ]);
        }
        else{
            return response()->json([
                'status' => '401',
                'message' => 'You Failed to Add a Supply'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showSupplies(Supplies $supplies)
    {
        $supplies_query = Supplies::query();
        $supplies = $supplies_query->paginate(10);

        if($supplies -> count() > 0){
            $SuppliesData = $supplies->map(function ($supply) {
                return [
                    'id' => $supply->id,
                    'supplier' => $supply->supplier->supplier_name,
                    'products' => $supply->products,
                    'quantity'=> $supply->quantity,
                    'set' => $supply->set 
                ];
            });
    
            return response()->json([
                'status' => '200',
                'message' => 'Found the Specific Data',
                'suppliers' => $SuppliesData,
                'pagination' => [
                    'current_page' => $supplies->currentPage(),
                    'total' => $supplies->total(),
                    'per_page' => $supplies->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }


    }

    public function showById($id){
        
        $supplies = Supplies::with(['supplier', 'products'])->find($id);
       
        if($supplies){
            $suppliesData = [
                'id' => $supplies->id,
                'supplier' => $supplies->supplier,
                'products' => $supplies->products,
                'quantity'=> $supplies->quantity,
                'set' => $supplies->set 
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'products' => $suppliesData,
            ]);
        }
      
        else {
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
            $softDeletedSupplies = Supplies::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedSupplies)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Supplies Data Found",
                    "product_type" => $softDeletedSupplies
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Supplies Data Found",
                ]);
            }
        } else {
                // Display the non-deleted records
                if ($id == 0) {
                    $activeSupplies = Supplies::all()->toArray();
                    if (!empty($activeSupplies)) {
                        return response()->json([
                            "status" => "200",
                            "message" => "Active Supplies Data Found",
                            "product_type" => $activeSupplies
                        ]);
                    } else {
                        return response()->json([
                            "status" => "404",
                            "message" => "No Active Supplies Data Found",
                        ]);
                    }
                }
            }
    }
    /**
     * Show the form for editing the specified resource.
     */
    // public function editSupplies(Supplies $supplies): View
    // {
       // supplies.edit the supplies here is a resource route for the web.php
    //     return view('supplies.editSupplies');
    // }

    /**
     * Update the specified resource in storage.
     */
    public function updateSupply(Request $request, Supplies $supplies)
    {
        $request->validate([
            // For digits length (Exact):
            'supplier_num' => 'required|integer|digits_between:1,10',
            // For String length (Exact): 'supplier_num' => 'required|integer|size:10',
            // For String Range : 'input' => 'required|string|min:5|max:10'
            // For digits Range : 'input' => 'required|digits_between:5,10'
            'products_ID' => 'required|integer|digits_between:1,10',
            'quantity' => 'required|integer|digits_between:1,8',
            'set' => 'required|string|max:255'
        ]);
        
        if($supplies->update($request->all())){
            return response()->json([
                "data" => $supplies,
                "status" => '200',
                "message" => "The Supply Data is Successfully Added"
            ]);
        }
        else{
            return response()->json([
                "data" => $supplies,
                "status" => '401',
                "message" => "Failed to Add the Supply Data "
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteSupply(Supplies $supplies)
    {
        if($supplies->delete()){
            return response([
                "status" => "200",
                "message" => "Successfully deleted the Supply Data",
                "data" => $supplies,
            ]);
        }
    }

         // Soft Delete
         public function softdeleterecord($supplies){

            $data = Supplies::find($supplies);
    
            if(!$data){
                return response()->json(
                    [
                        'status' => 404,
                        'message' => 'Supplies not found',
                    ]);
            }
            $data->delete();
            return response()->json(
                [
                    'status' => 201,
                    'message' => 'Supplies Soft Deleted Successfully',
                    'data' => $data
                ]);
    
        }

}

