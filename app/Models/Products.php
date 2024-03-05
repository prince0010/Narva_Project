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
        'supplier_ID', //Fk
        'part_num',
        'part_name',
        'brand',
        'model',
        'price_code',
        'stock'
    ];

    public function prod_type(){
        return $this->belongsTo(Prod_Types::class, 'prod_type_ID');
    }

    public function sales(){
        return $this ->hasOne(sales::class);
    }
   
   public function supplier(){
        return $this ->belongsTo(Supplier::class, 'supplier_ID');
   }


//    Para sa Subtract Stock

   public function subtractStock(int $quantity): bool
   {
    if($quantity <=0 || $this->stock < $quantity){
        return false;
    }
    
    $this->stock -= $quantity;
    return $this->save();
   }

//   Para sa Add Stock

   public function addStock(int $quantity): bool
   {
    if($quantity <= 0 ){
        return false;
    }
   
    $this->stock += $quantity;
    return $this->save();
   }
}
