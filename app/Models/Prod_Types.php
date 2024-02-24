<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prod_Types extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name'
    ];

    public function product(){
        return $this ->hasMany(Products::class);
    }
}
