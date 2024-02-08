<?php

namespace App\Models;

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuppliesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    protected $fillable = [
        'supplier_name'
    ];
}
