<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class transaction_details extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transaction_details';

    protected $fillable = [
        // Define other fillable attributes here
    ];

    public function getTotalChargeAttribute()
    {
        return $this->charge;
        // You may adjust this depending on how you calculate the total charge
    }

    public function getTotalDownpaymentAttribute()
    {
        return $this->downpayment_info()->sum('downpayment');
    }

    public function getBalanceAttribute()
    {
        return $this->total_charge - $this->total_downpayment;
    }

    public function getStatusAttribute()
    {
        return $this->balance == 0 ? 'Fully Paid' : 'Not Paid';
    }

    public function downpayment_info()
    {
        return $this->hasMany(downpayment_info::class);
    }

}
