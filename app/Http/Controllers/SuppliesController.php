<?php

namespace App\Http\Controllers;

use App\Models\Supplies;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuppliesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Supplies::latest()->paginate(5);


        return view('supplies.index', compact('supplies'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('supplies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addSupply(Request $request)
    {
        $request -> validate([
            // For digits length (Exact):
             'supplier_num' => 'required|integer|digits_between:1,10',
            // For String length (Exact): 'supplier_num' => 'required|integer|size:10',
            // For String Range : 'input' => 'required|string|min:5|max:10'
            // For digits Range : 'input' => 'required|digits_between:5,10'
            'part_num' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'code' => 'required|string|max:255', // In Specific Code there is a price on it so example in RRNB the price of it is Pesos 3150.00
            'quantity' => 'required|integer|digits_between:1,8',
        ]);

        $supply = Supplies::create($request->all());

        if($supply){
            return response()->json([
                'status' => '200',
                'message' => 'You Successfully Add a Supply',
                "data" => $supply,
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
        return response()->json($supplies);
    }

    public function showSuppliesAll()
    {
        //
        $supplies = Supplies::all()->toArray();
        return response()->json($supplies);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplies $supplies): View
    {
        // supplies.edit the supplies here is a resource route for the web.php
        return view('supplies.edit');
    }

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
            'part_num' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'code' => 'required|string|max:255', // In Specific Code there is a price on it so example in RRNB the price of it is Pesos 3150.00
            'quantity' => 'required|integer|digits_between:1,8',
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
}