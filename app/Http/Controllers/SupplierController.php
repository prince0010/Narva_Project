<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Supplier::latest()->paginate(5);


        return view('supplier.index', compact('supplier'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request -> validate([
            'supplier_name' =>'required|string|max:255'
        ]);

        $supplier = Supplier::create($request->all());

        if($supplier)
        {
            return response()->json([
                $supplier,
                "status" => '200',
                "message" => "Supplier Name Added Successfully"
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
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function showAll()
    {
        $supplier = Supplier::all()->toArray();
        return response()->json($supplier);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'supplier_name' =>'required|string|max:255'
        ]);

        if ($supplier->update($request->all())) {
          
            return response()->json([
                $supplier,
                'status' => 200,
                "message" => "You Updated the Supplier Name Successfully",
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
    public function destroy(Supplier $supplier)
    {
        if ($supplier->delete()) {
            // return redirect()->route('products.index')
            // ->with(response()->json([
            //     "status" => 200,
            //     "message" => "Product Deleted Sucessfully",
            // ]));
            return response()->json([
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
}
