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
        'credit_date', //Mao ning Date na ilang ihatag sa mga nangutang like 30 days or 60 days depende sa ila so input lang nio na date dapat
        'invoice_number',
        'charge',
        'credit_limit',
        'balance',
        'status'
    ];


    public function credit_names(){
        return $this->hasOne(credit_names::class);
    }

}
