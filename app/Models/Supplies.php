<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplies extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supplier_num', //Fk
        'products_ID', //Fk
        'quantity',
        'set',
    ];


    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_num');
    }


    public function products(){
        return $this->belongsTo(Products::class, 'products_ID');
    }
}
