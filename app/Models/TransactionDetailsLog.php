<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetailsLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transaction_details_logs';

    protected $fillable = [
        'cred_inform_id',
        'old_total_downpayment',
        'new_total_downpayment',
        'old_total_charge',
        'new_total_charge',
        'old_balance',
        'new_balance',
        'old_status',
        'new_status',
    ];

    public function cred_inform(){
        return $this->belongsTo(credit_inform::class, 'cred_inform_id');
    }
}
