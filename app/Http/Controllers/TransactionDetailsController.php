<?php

namespace App\Http\Controllers;

use App\Models\credit_inform;
use App\Models\transaction_details;
use App\Models\TransactionDetailsLog;
use Illuminate\Http\Request;

class TransactionDetailsController extends Controller
{
    public function index(Request $request)
    {
        $transaction_query = transaction_details::query();

        $req = $request->keyword;
        if ($req) {
            $transaction_query->where('status', 'LIKE', '%' . $req . '%')
            ->orWhere('total_downpayment', 'LIKE', '%' . $req . '%')
            ->orWhere('total_charge', 'LIKE', '%' . $req . '%')
                ->orWhere('balance', 'LIKE', '%' . $req . '%');
        }
        $transaction_details = $transaction_query->paginate(10);

        if ($transaction_details->count() > 0) {
            $Transaction_Data = $transaction_details->map(function ($transac_det) {
                return [
                    'transaction_details_id' => $transac_det->id,
                    'cred_inform_id' => $transac_det->credit_inform ? $transac_det->credit_inform : null,
                    'total_downpayment' => $transac_det->total_downpayment ? $transac_det->total_downpayment : null,
                    'total_charge' => $transac_det->total_charge ? $transac_det->total_charge : null,
                    'balance'=>  $transac_det->balance ? $transac_det->balance : null,
                    'status'=>  $transac_det->status ? $transac_det->status : null,
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'credit_names' => $Transaction_Data,
                'pagination' => [
                    'current_page' => $transaction_details->currentPage(),
                    'total' => $transaction_details->total(),
                    'per_page' => $transaction_details->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Transaction Details is empty'
            ]);
        }
    }

     //  Search
     public function searchTransactionDetails($transaction_details)
     {
             $transac_deta = transaction_details::select('transaction_details.*')
            ->join('credit_inform', 'transaction_details.cred_inform_id', '=', 'credit_inform.id')
            ->where('credit_inform.invoice_number', 'like', '%' . $transaction_details . '%')
            ->orWhere('status', 'like', '%' . $transaction_details . '%')
            ->get();

         if (empty(trim($transaction_details))) {
             return response()->json([
                 "status" => "204",
                 "message" => "No Input is Provided for Search",
             ]);
         } else {
             return response()->json($transac_deta);
         }
     }

     public function showById($id)
    {

        $transac_details = transaction_details::with('credit_inform')->find($id);

        if ($transac_details) {
            $transac_data = [
                "transaction_details" => [
                    "transaction_details_id" => $transac_details->id,
                    "cred_inform_id" => $transac_details->credit_inform,
                    "total_downpayment" => $transac_details->total_downpayment ? $transac_details->total_downpayment : null,
                    "total_charge" => $transac_details->total_charge ? $transac_details->total_charge : null,
                    "balance" => $transac_details->balance ? $transac_details->balance : null,
                    "status" => $transac_details->status ? $transac_details->status : null,
                ],
               
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'sales' => $transac_data,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }
    public function showTransactionDetails(transaction_details $transaction_details)
    {

        $transaction_que = $transaction_details->get();

        if ($transaction_que->count() > 0) {
            $Transaction_Data = $transaction_que->map(function ($transact_details) {
                return [
                    "transaction_details" => [
                        "transaction_details_id" => $transact_details->id,
                        "cred_inform_id" => $transact_details->credit_inform,
                        "total_downpayment" => $transact_details->total_downpayment ? $transact_details->total_downpayment : null,
                        "total_charge" => $transact_details->total_charge ? $transact_details->total_charge : null,
                        "balance" => $transact_details->balance ? $transact_details->balance : null,
                        "status" => $transact_details->status ? $transact_details->status : null,
                    ],

                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'sales' => $Transaction_Data,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }

    public function showSoftDeletedTransactionDetails($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $SoftDeletedTransactionDetails = transaction_details::onlyTrashed()->get()->toArray();
            if (!empty($SoftDeletedTransactionDetails)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Transaction Details Found",
                    "transaction_details" => $SoftDeletedTransactionDetails
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Transaction Details Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            $activeTransactionDetails = transaction_details::all()->toArray();
            if (!empty($activeTransactionDetails)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Active Transaction Details Found",
                    "transaction_details" => $activeTransactionDetails
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Active Transaction Details Found",
                ]);
            }
        }
    }
    // Soft Delete
    public function softdeleterecord($transaction_details)
    {

        $data = transaction_details::find($transaction_details);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Transaction Details not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Transaction Details Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }

    public static function calculateTransactionDetails($creditInformId)
{
    $creditInform = credit_inform::find($creditInformId);

    if (!$creditInform) {
        return ['message' => 'Credit Inform not found.'];
    }

    $totalDownpayment = $creditInform->downpayment_info()->sum('downpayment');
    $totalCharge = $creditInform->sum('charge');
    $balance = $totalCharge - $totalDownpayment;
    $status = $balance === 0 ? 'Paid' : 'Not Fully Paid';

    // Check if the total charge exceeds the credit limit
    $creditLimit = $creditInform->credit_users->credit_limit;

    if ($totalCharge > $creditLimit) {
        return ['message' => 'Charge exceeds credit limit.'];
    }

    return [
        'cred_inform_id' => $creditInformId,
        'total_downpayment' => $totalDownpayment,
        'total_charge' => $totalCharge,
        'balance' => $balance,
        'status' => $status,
    ];
}

    // public function calculateTransactionDetails($creditInformId)
    // {
    //     // Fetch the credit inform details
    //     $creditInform = credit_inform::find($creditInformId);

    //     if (!$creditInform) {
    //         return response()->json(['error' => 'Credit Inform not found.'], 404);
    //     }

    //     // Perform the calculations
    //     $totalDownpayment = $creditInform->downpayment_info->sum('downpayment');
    //     $totalCred = $creditInform->sum('charge');
    //     $balance = $totalCred - $totalDownpayment;
    //     $status = $balance == 0 ? 'Fully Paid' : 'Not Fully Paid';

    //     // Create or update the transaction details
    //     $transactionDetails = transaction_details::updateOrCreate(
    //         ['cred_inform_id' => $creditInformId],
    //         [
    //             'total_downpayment' => $totalDownpayment,
    //             'total_charge' => $totalCred,
    //             'balance' => $balance,
    //             'status' => $status,
    //         ]
    //     );

    //     // Log the past and new updates
    //     $this->logTransactionDetailsUpdate($creditInformId, $transactionDetails->getOriginal(), $transactionDetails->getAttributes());

    //     return response()->json($transactionDetails);
    // }

    // private function logTransactionDetailsUpdate($creditInformId, $oldValues, $newValues)
    // {
    //     TransactionDetailsLog::create([
    //         'cred_inform_id' => $creditInformId,
    //         'old_total_downpayment' => $oldValues['total_downpayment'],
    //         'new_total_downpayment' => $newValues['total_downpayment'],
    //         'old_total_charge' => $oldValues['total_charge'],
    //         'new_total_charge' => $newValues['total_charge'],
    //         'old_balance' => $oldValues['balance'],
    //         'new_balance' => $newValues['balance'],
    //         'old_status' => $oldValues['status'],
    //         'new_status' => $newValues['status'],
    //     ]);
    // }
}
