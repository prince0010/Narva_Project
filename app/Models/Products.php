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
        'prod_type_ID',
        'part_num',
        'part_name',
        'brand',
        'model',
        'price_code',
    ];

    public function prod_type(){
        return $this->belongsTo(Prod_Types::class);
    }

    public function supply(){
        return $this->hasMany(Supplies::class);
    }
    // Foreign and Primary Example
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
}
