<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_info extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'credit_info'; 
    protected $fillable = [
      
        'credit_names_id',
        'total_charge',
        'total_downpayment',
        'balance',
        'status'
    ];


    public function credit_names(){
        return $this->belongsTo(credit_names::class, 'credit_names_id');
    }

    // Credit_Info model
public function getTotalChargeAttribute()
{
    return $this->credit_names->sum('charge');
}

public function getTotalDownpaymentAttribute()
{
    return $this->credit_names->sum('downpayment');
}

public function getBalanceAttribute()
{
    return $this->total_charge - $this->total_downpayment;
}

}
