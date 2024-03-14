<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class downpayment_info extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'downpayment_info';

    protected $fillable = [
        'downpayment',
        'dp_date',
        'credit_inform_id', // Add the credit_inform_id to the fillable array
    ];

    // Define the relationship with credit_inform
    public function credit_inform()
    {
        return $this->belongsTo(credit_inform::class);
    }
}
