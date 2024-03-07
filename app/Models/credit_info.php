<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_info extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'dp_ID',
        'credit_date',
        'invoice_number',
        'charge',
        'credit_limit',
        'balance',
        'status'
    ];

    public function downpayment_info(){
        return $this->belongsTo(downpayment_info::class, 'dp_ID')->withTrashed();
    }
    
}
