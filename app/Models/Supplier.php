<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'supplier_name',
        'contact_number'
    ];

    public function product(){
        return $this ->hasMany(Products::class);
    }


}
