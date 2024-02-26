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
     public function searchSupplies($supplies){
        $supp = Supplies::where('supplier_num', 'like', '%'.$supplies.'%')->get();
 
        if(empty(trim($supplies))) {
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

    public function showSuppliesAll()
    {
        //
      
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
                'message' => 'Found Data',
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


      /**
     * Display a listing of the resource.
     */
    // public function Suppliesindex()
    // {
    //     $product = Supplies::latest()->paginate(5);


    //     return view('supplies.Suppliesindex', compact('supplies'))
    //         ->with('i', (request()->input('page', 1) - 1) * 10);
    //     // llike forloop
    // }

    /**
     * Show the form for creating a new resource.
     */
    // public function createSupplies() : View
    // {
    //     return view('supplies.createSupplies');
    // }

}

