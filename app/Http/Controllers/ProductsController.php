<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  View from Illuminate\View\View must have a return view() function para dili mag error og para dili ma void ang View 
    public function index() : View
    {
        // Display a listing of the Data

        $product = Products::latest()->paginate(5);


        return view('products.index', compact('products'))
        ->with('i', (request()-> input('page', 1) - 1) * 10);
            // llike forloop
    }

    /**
     * Show and redirect to the form for creating a new resource.
     */
    public function create() : View
    {
        //
          
        return view('products.create');

    }

    /**
     * Store a newly created resource in storage.
     */

    //  RedirectResponse from Illuminate\Http\RedirectResponse must have a return redirect() function para dili mag error og para dili ma void ang RedirectResponse 
    public function store(Request $request) : RedirectResponse
    {
        //
        $request->validate([
            'product_name' => 'required|string|max:15',
            'product_details' => 'required|string|max:255',
            'quantity' => 'required|string|max:255'
        ]);

        if(Products::create($request-> all()))
{
    return redirect()->route('products.index')
    ->with(response()->json([
        'status' => 200,
        "message" => "Added the Product Successfully",
    ]));
}       

       
    }


    /**
     * Display the specified resource.
     */
    public function show(Products $products) : View
    {
        //  
        return view('products.show', compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products) : View
    {
        //
        return view('products.edit', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $products) : RedirectResponse
    {
        //
        $request->validate([
            'product_name' => 'required',
            'product_details' => 'required',
            'quantity' => 'required'
        ]);

            if(Products::create($request-> all())){
                return redirect()->route('products.index')
                ->with(response()->json([
                    'status' => 200,
                    "message" => "You Updated the Product Successfully",
                ]));
            }
            else{
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
    public function destroy(Products $products) : RedirectResponse
    {
        //
        if($products->delete()){
            return redirect()->route('products.index')
            ->with(response()->json([
                "status" => 200,
                "message" => "Product Deleted Sucessfully",
            ]));
          
        }
        else{
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Product",
            ]);
        }
       

      
    }

}
