<?php

namespace App\Http\Controllers;

use App\Models\downpayment_info;
use Illuminate\Http\Request;

class DpInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'downpayment' => 'required|integer|digits_between:1, 999',
            'dp_date' => 'required|date|date_format:Y-m-d'
        ]);

        $dp_info = downpayment_info::create($request->all());

        if(!$dp_info){
            return response()->json([
                'status' => 500,
                'message' => 'Failed to Add Downpayment'
            ]);
        }
        else{
            return response()->json([
                
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(downpayment_info $downpayment_info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(downpayment_info $downpayment_info)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, downpayment_info $downpayment_info)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(downpayment_info $downpayment_info)
    {
        //
    }
}
