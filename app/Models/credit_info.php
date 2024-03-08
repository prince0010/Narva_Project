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
        'credit_date', //Mao ning Date na ilang ihatag sa mga nangutang like 30 days or 60 days depende sa ila so input lang nio na date dapat
        'credit_name_ID',
        'invoice_number',
        'charge',
        'credit_limit',
        'balance',
        'status'
    ];


    public function credit_names(){
        return $this->belongsTo(credit_names::class, 'credit_name_ID');
    }

}
