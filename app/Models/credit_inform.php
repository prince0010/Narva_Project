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
    ];

    public function credit_users()
    {
        return $this->belongsTo(credit_users::class, 'credit_users_id');
    }

    public function downpayment_info()
    {
        return $this->hasMany(downpayment_info::class, 'credit_inform_id');
    }

    // Add this method to get the total downpayment for a specific credit_inform
    public function getTotalDownpaymentAttribute()
    {
        return $this->downpayment_info()->sum('downpayment');
    }

    // Add this method to calculate the remaining charge after deducting downpayment
    public function getRemainingChargeAttribute()
    {
        return $this->charge - $this->getTotalDownpaymentAttribute();
    }

    public function transaction_details()
    {
        return $this->hasMany(transaction_details::class);
    }

    public function transaction_details_log()
    {
        return $this->hasMany(TransactionDetailsLog::class);
    }
}