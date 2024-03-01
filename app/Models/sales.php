<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id', 
        'interest_id',
        'quantity',
        'total',
        'sale_date',
        'remarks'
        
    ];


    public function interest(){
        return $this->belongsTo(interest::class, 'interest_id');
    }

    public function product(){
        return $this->belongsTo(Products::class, 'product_id')->with('prod_type');
    }
}
