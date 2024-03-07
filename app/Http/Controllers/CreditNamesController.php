<?php

namespace App\Http\Controllers;

use App\Models\credit_names;
use Illuminate\Http\Request;

class CreditNamesController extends Controller
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
            'credit_name',
            'credit_info_ID'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(credit_names $credit_names)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(credit_names $credit_names)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, credit_names $credit_names)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(credit_names $credit_names)
    {
        //
    }
}
