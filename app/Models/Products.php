<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;


     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_type_ID',
        'product_type_ID',
        'part_name',
        'brand',
        'model',
        'price_code',
    ];

    // Foreign and Primary Example
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
}
