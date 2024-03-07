<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class downpayment_info extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'downpayment',
        'dp_date'
    ];

    public function credit_info(){
      return $this->hasMany(credit_info::class); 
    }
}
