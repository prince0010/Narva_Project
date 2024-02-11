<?php

namespace App\Http\Controllers;

use App\Models\Prod_Types;
use App\Models\Product_Type;
use App\Models\Products;
use App\Models\Supplier;
use App\Models\Supplies;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function registerUser(): View {
        return view('pages.registerUser');
    }
    public function loginUser(): View {
        return view('pages.loginUser');
    }
    public function profileUser(): View {
        return view('pages.profileUser');
    }
    // public function logoutUser(): View {
    //     return view('pages.loginUser');
    // }

    // Product
    public function Productindex(): View
    {
        // Display a listing of the Data

        $product = Products::latest()->paginate(5);

        return view('pages.Productindex', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    public function editProduct(Products $products): View
    {
        // products.edit the products here is a resource route for the web.php
        return view('pages.editProduct', compact('products'));
    }

    public function createProduct(): View
    {
        // // products.create the products here is a resource route for the web.php

        return view('pages.createProduct');
    }


    // Product Type
    public function Producttypeindex(): View
    {
        // Display a listing of the Data

        $product = Prod_Types::latest()->paginate(5);

        return view('pages.Producttypeindex', compact('product_type'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    public function editProducttype(Prod_Types $products): View
    {
        // products.edit the products here is a resource route for the web.php
        return view('pages.editProducttype', compact('product_type'));
    }

    public function createProducttype(): View
    {
        // // products.create the products here is a resource route for the web.php

        return view('pages.createProducttype');
    }




    // Supplier
    public function Supplierindex()
    {
        $product = Supplier::latest()->paginate(5);


        return view('pages.Supplierindex', compact('supplier'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    public function createSupplier() : View
    {
        //  supplier.create the supplier here is a resource route for the web.php
        return view('pages.createSupplier');
    }

    public function editSupplier(Supplier $supplier): View
    {
        // supplier.edit the supplier here is a resource route for the web.php
        return view('pages.editSupplier');
    }


    // Supplies
    public function Suppliesindex()
    {
        $product = Supplies::latest()->paginate(5);


        return view('pages.Suppliesindex', compact('supplies'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        // llike forloop
    }

    public function createSupplies() : View
    {
        return view('pages.createSupplies');
    }

    public function editSupplies(Supplies $supplies): View
    {
        // supplies.edit the supplies here is a resource route for the web.php
        return view('pages.editSupplies');
    }

}
