<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_inform extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'credit_inform';

    protected $fillable = [
        'credit_users_id',
        'credit_date',
        'invoice_number',
        'charge',
        'downpayment_info_id',
        'status'
    ];

    public function credit_users(){
        return $this->belongsTo(credit_users::class, 'credit_users_id');
    }

    public function downpayment_info(){
        return $this->belongsTo(downpayment_info::class, 'downpayment_info_id');
    }

    // Add this method to get the total downpayment for a specific downpayment_info_id
    public function getTotalDownpaymentAttribute()
    {
        if ($this->credit_inform) {
            return $this->credit_inform->sum('downpayment');
        } else {
            return 0; // or any default value you prefer
        }
    }

    // Add this method to deduct downpayment from the associated charge
    public function deductDownpayment()
    {
        $remainingCharge = $this->charge - $this->getTotalDownpaymentAttribute();

        return $remainingCharge;
    }

    public function transaction_details(){
        return $this->hasMany(transaction_details::class);
    }

    public function transaction_details_log(){
        return $this->hasMany(TransactionDetailsLog::class);
    }

    public function addDownpayment($amount)
    {
        $remainingCharge = $this->deductDownpayment();

        if ($amount > $remainingCharge) {
            return false; // Downpayment exceeds remaining charge
        }

        // Create a new downpayment
        $this->downpayment_info()->create([
            'downpayment' => $amount,
            'dp_date' => now(), // You may adjust the date as needed
        ]);

        return true; // Downpayment added successfully
    }
}

