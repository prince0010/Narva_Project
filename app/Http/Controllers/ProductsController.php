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
    public function index(): View
    {
        // Display a listing of the Data

        $product = Products::latest()->paginate(5);


        return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    // REDIRECT 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products): View
    {
        //
        return view('products.edit', compact('products'));
    }

    /**
     * Show and redirect to the form for creating a new resource.
     */
    public function create(): View
    {
        //

        return view('products.create');
    }
    // REDIRECT 
    /**
     * Store a newly created resource in storage.
     */

    //  RedirectResponse from Illuminate\Http\RedirectResponse must have a return redirect() function para dili mag error og para dili ma void ang RedirectResponse 
    // public function store(Request $request): RedirectResponse
    public function store(Request $request, Products $product)
    {
        //
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_details' => 'required|string|max:255',
            'quantity' => 'required'
        ]);
        $product = Products::create($request->all());
        if ($product) {
            return response()->json([
                $product,
                    "status" => 200,
                    "message" => "Added the Product Successfully",
                ]);
            // return redirect()->route('products.index')
            //     ->with(response()->json([
            //         'status' => 200,
            //         "message" => "Added the Product Successfully",
            //     ]));

            //    return response()->json([
            //         'status' => 200,
            //         "message" => "Added the Product Successfully",
            //     ]);
        } else {
            return response()->json([
               
                "status" => 401,
                "message" => "Failed to Add a Product",
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(Products $products) : View
    public function show(Products $products)
    {
        // return view('products.show', compact('products'));
        return response()->json($products);
    }

    public function showAll(Products $products)
    {
        // return view('products.show', compact('products'));
        $products = Products::all()->toArray();
        return response()->json(
            [
            "Products" => $products
        ]
    );
    }



    /**
     * Update the specified resource in storage.
     */
    // UPDATE = PUT
    // If you try to update in POSTMAN use the Body -> x-www-form-urlencoded to edit the user with its specific ID and you must put the table data's example here: product_name, ppproduct_details, quantity 
    // public function update(Request $request, Products $products) : RedirectResponse
    public function update(Request $request, Products $products)
    {

        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_details' => 'required|string|max:255',
            'quantity' => 'required'
        ]);

        if ($products->update($request->all())) {
            // return redirect()->route('products.index')
            // ->with(response()->json([
            //     'status' => 200,
            //     "message" => "You Updated the Product Successfully",
            // ]));
            return response()->json([
                $products,
                'status' => 200,
                "message" => "You Updated the Product Successfully",
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
    public function destroy(Products $products)
    {
        //
        if ($products->delete()) {
            // return redirect()->route('products.index')
            // ->with(response()->json([
            //     "status" => 200,
            //     "message" => "Product Deleted Sucessfully",
            // ]));
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Product Successfully",
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Product",
            ]);
        }
    }
}
