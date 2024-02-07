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
                $supply,
                'status' => '200',
                'message' => 'You Successfully Add a Supply'
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
    public function show(Supplies $supplies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplies $supplies): View
    {
        //
        return view('supplies.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplies $supplies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplies $supplies)
    {
        //
    }
}
