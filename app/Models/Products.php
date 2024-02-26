<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prod_type_ID', //Fk
        'part_num',
        'part_name',
        'brand',
        'model',
        'price_code',
    ];

    public function prod_type(){
        return $this->belongsTo(Prod_Types::class, 'prod_type_ID');
    }

    public function supply(){
        return $this->hasMany(Supplies::class);
    }
    // Foreign and Primary Example
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
}
