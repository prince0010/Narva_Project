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
        'cred_inform_id',
        'total_downpayment',
        'total_charge',
        'balance',
        'status'
    ];


    public function credit_inform(){
        return $this->belongsTo(credit_inform::class, 'cred_inform_id');
    }


    // public static function getTransactionDetailsByCreditInformId($creditInformId)
    // {
    //     // Fetch transaction details based on cred_inform_id
    //     $transactionDetails = self::where('cred_inform_id', $creditInformId)->first();

    //     // If transaction details are found, return the data
    //     if ($transactionDetails) {
    //         return [
    //             'cred_inform_id' => $transactionDetails->cred_inform_id,
    //             'total_downpayment' => $transactionDetails->total_downpayment,
    //             'total_cred' => $transactionDetails->total_cred,
    //             'balance' => $transactionDetails->balance,
    //             'status' => $transactionDetails->status,
    //         ];
    //     } else {
        
    //         return [
    //             'message' => 'No Transaction Details Found. Try Again.'
    //         ];
    //     }
    // }

    public static function calculateTransactionDetails($creditInformId)
    {
        $creditInform = credit_inform::find($creditInformId);

        if (!$creditInform) {
            return ['message' => 'Credit Inform not found.'];
        }

        $totalDownpayment = $creditInform->downpayment_info()->sum('downpayment');
        $totalCred = $creditInform->sum('charge');
        $balance = $totalCred - $totalDownpayment ;
        $status = $balance === 0 ? 'Paid' : 'Not Fully Paid';

        return [
            'cred_inform_id' => $creditInformId,
            'total_downpayment' => $totalDownpayment,
            'total_charge' => $totalCred,
            'balance' => $balance,
            'status' => $status,
        ];
    }
}
