<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit_Info extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'credit_information'; 
    //Table Name
    protected $fillable = [
        'debit_date',
        'invoice_number',
        'charge',
        'credit_limit',
        'dp_date',
        'downpayment',
        'balance',
        'status'
    ];

    // public function creditinfo(){
    //     return $this ->hasMany(Credit_Names::class);
    // }
}
