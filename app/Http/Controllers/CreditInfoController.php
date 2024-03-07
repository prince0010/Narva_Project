<?php

namespace App\Http\Controllers;

use App\Models\credit_info;
use Illuminate\Http\Request;

class CreditInfoController extends Controller
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
            
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(credit_info $credit_info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(credit_info $credit_info)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, credit_info $credit_info)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(credit_info $credit_info)
    {
        //
    }
}
