<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prod_Types extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_type_name'
    ];

    public function product(){
        return $this ->hasMany(Products::class);
    }
}
