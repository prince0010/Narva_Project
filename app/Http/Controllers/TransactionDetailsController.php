<?php

namespace App\Http\Controllers;

use App\Models\credit_inform;
use App\Models\transaction_details;
use Illuminate\Http\Request;

class TransactionDetailsController extends Controller
{
    public function index(Request $request)
    {
        $credit_informs = credit_inform::query();
        $req = $request->keyword;
        if ($req) {
            $credit_informs->where('invoice_number', 'LIKE', '%' . $req . '%')
                ->orWhere('credit_date', 'LIKE', '%' . $req . '%')
                ->orWhere('status', 'LIKE', '%' . $req . '%')
                ->orWhere('charge', 'LIKE', '%' . $req . '%');
        }
    
        $credit_info = $credit_informs->paginate(10);
    
        if ($credit_info->count() > 0) {
            $creditInformData = $credit_info->map(function ($cred_info) {
                return [
                    'credit_inform_id' => $cred_info->id,
                    'credit_users_id' => $cred_info->credit_users ? $cred_info->credit_users->credit_name : null ,
                    'downpayment_info_id' => $cred_info->downpayment_info->isNotEmpty() ? $cred_info->downpayment_info->map(function ($downpayment) {
                        return [
                            'id' => $downpayment->id,
                            'downpayment' => $downpayment->downpayment,
                            'dp_date' => $downpayment->dp_date,
                        ];
                    }) : 'No Downpayment Data',   
                    'credit_date' => $cred_info->credit_date,
                    'invoice_number' => $cred_info->invoice_number,
                    'charge' => $cred_info->charge,
                ];
            });
    
            // Calculate overall total charge, downpayment, and status
            $overallTotalCharge = $credit_info->sum('charge');
            $overallTotalDownpayment = $credit_info->sum(function ($cred_info) {
                return $cred_info->getTotalDownpaymentAttribute();
            });
            $overallBalance = $overallTotalCharge - $overallTotalDownpayment;
            $overallStatus = $overallBalance == 0 ? 'Fully Paid' : 'Not Paid';
    
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'credit_information' => $creditInformData,
                'overall_total_charge' => $overallTotalCharge,
                'overall_total_downpayment' => $overallTotalDownpayment,
                'overall_balance' => $overallBalance,
                'overall_status' => $overallStatus,
                'pagination' => [
                    'current_page' => $credit_info->currentPage(),
                    'total' => $credit_info->total(),
                    'per_page' => $credit_info->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Credit Information is empty'
            ]);
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

 
}
