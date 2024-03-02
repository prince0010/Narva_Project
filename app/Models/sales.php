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
        'product_id', //Fk
        'markup_id', //Fk
        'quantity',
        'total',
        'sale_date',
        'remarks'
        
    ];


    public function markup(){
        return $this->belongsTo(markup::class, 'markup_id');
    }

    public function product(){
        return $this->belongsTo(Products::class, 'product_id')->with('prod_type');
    }
}
