<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_users extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'credit_users';
    
    protected $fillable = [
        'credit_name',
        'credit_limit'
    ];

    protected $guarded = ['credit_limit'];
    
    public function credit_inform()
    {
        return $this->hasMany(credit_inform::class, 'credit_users_id');
    }

    public function transaction_details()
    {
        return $this->hasManyThrough(
            transaction_details::class,
            credit_inform::class,
            'credit_users_id', 
            'credit_inform_id', 
            'id', 
            'id'
        );
    }

    public function downpayment_info()
    {
        // Assuming downpayment_info is a relationship on the credit_inform model
        return $this->hasManyThrough(
            downpayment_info::class,
            credit_inform::class,
            'credit_users_id', // Foreign key on credit_inform table
            'credit_inform_id', // Foreign key on downpayment_info table
            'id', // Local key on credit_users table
            'id' // Local key on credit_inform table
        );
    }

    public function getTotalChargeAttribute()
    {
        return $this->transaction_details()->sum('charge');
    }

    public function getTotalDownpaymentAttribute()
    {
        return $this->downpayment_info()->sum('downpayment');
    }

    public function getFullyPaidAttribute()
    {
        return $this->total_downpayment == $this->total_charge;
    }
}