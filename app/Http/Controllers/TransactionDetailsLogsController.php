<?php

namespace App\Http\Controllers;

use App\Models\credit_inform;
use App\Models\credit_users;
use App\Models\TransactionDetailsLog;
use Illuminate\Http\Request;

class TransactionDetailsLogsController extends Controller
{
    public function getTransactionDetailsLogs($creditInformId)
    {
        $logs = TransactionDetailsLog::with(['cred_inform.credit_users'])
        ->where('cred_inform_id', $creditInformId)
        ->get();

    $formattedLogs = $logs->map(function ($log) {
        return [
            'log_id' => $log->id,
            'cred_inform_id' => [
                'id' => $log->cred_inform_id,
                'credit_users' => [
                    'credit_users_id' => $log->cred_inform->credit_users->id,
                    'credit_name'=>$log->cred_inform->credit_users->credit_name,
                    'credit_limit' => $log->cred_inform->credit_users->credit_limit,
                ],
                'credit_date' => $log->cred_inform->credit_date,
                'invoice_number' => $log->cred_inform->invoice_number,
                'charge' => $log->cred_inform->charge,
              
                'downpayment_info_id' => $log->cred_inform->downpayment_info_id,
                'status' => $log->cred_inform->status,
                'created_at' => $log->cred_inform->created_at,
                'updated_at' => $log->cred_inform->updated_at,
                'deleted_at' => $log->cred_inform->deleted_at,
            ],
            'old_details' => json_decode($log->old_values, true),
            'new_details' => json_decode($log->new_values, true),
            'created_at' => $log->created_at,
            'updated_at' => $log->updated_at,
        ];
    });

    return response()->json($formattedLogs);
}

}
