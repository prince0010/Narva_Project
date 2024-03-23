<?php

namespace App\Http\Controllers;

use App\Models\credit_inform;
use App\Models\credit_users;
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

    public function getCreditAndDownpaymentInfo(Request $request, $credit_users_id)
    {
        $creditUser = credit_users::find($credit_users_id);
        
        if (!$creditUser) {
            return response()->json([
                'status' => 404,
                'message' => 'Credit user not found for the given ID.',
            ]);
        }
    
        $creditInformsQuery = credit_inform::where('credit_users_id', $credit_users_id);
    
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $creditInformsQuery->where('invoice_number', 'LIKE', '%' . $keyword . '%');
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'The request must include a "keyword" parameter for filtering.',
            ]);
        }
    
        // Retrieve all credit informs without pagination
        $creditInforms = $creditInformsQuery->get();
        
        $creditInformsWithDownpayment = [];
        $overallStatus = 'Paid'; 
        $totalCharge = 0; 
        $overallDownpayment = 0; // Initialize overall downpayment to 0
    
        foreach ($creditInforms as $creditInform) {
            $downpaymentInfo = $creditInform->downpayment_info()->get();
            $downpaymentTotal = $downpaymentInfo->sum('downpayment'); 
    
            if ($downpaymentTotal < $creditInform->charge) {
                $overallStatus = 'Not Fully Paid';
            }
    
            $totalCharge += $creditInform->charge; 
            $overallDownpayment += $downpaymentTotal; // Accumulate overall downpayment
    
            $creditInformsWithDownpayment[] = [
                'credit_inform' => [
                    'id' =>  $creditInform->id,
                    'credit_users_id' => $creditUser,
                    'credit_date' => $creditInform->credit_date,
                    'invoice_number' => $creditInform->invoice_number,
                    'charge' => $creditInform->charge,
                    'created_at' => $creditInform->created_at,
                    'updated_at' => $creditInform->updated_at,
                    'deleted_at' =>  $creditInform->deleted_at,
                ],
                'downpayment_info' => $downpaymentInfo,
                'downpayment_total' => $downpaymentTotal,
                'total_charge' => $totalCharge,
            ];
        }
    
        // Calculate balance
        $balance = $totalCharge - $overallDownpayment;
    
        $pagination = null; // Remove pagination from the response
    
        return response()->json([
            'status' => 200,
            'message' => 'Credit and downpayment information retrieved successfully.',
            'credit_informs_with_downpayment' => $creditInformsWithDownpayment,
            'overall_downpayment' => $overallDownpayment,
            'total_charge' => $totalCharge, 
            'balance' => $balance,
            'overall_status' => $overallStatus,
            'pagination' => $pagination,
        ]);
    }
    

    public function showByCreditName($credit_name)
    {
        $creditUser = credit_users::where('credit_name', $credit_name)->first();
    
        if (!$creditUser) {
            return response()->json([
                'status' => '404',
                'message' => 'Credit user not found for the given credit name.',
            ]);
        }
    
        // Find the transaction details associated with the credit user
        $transac_details = transaction_details::whereHas('credit_inform', function ($query) use ($creditUser) {
            $query->where('credit_users_id', $creditUser->id);
        })->first();
    
        if ($transac_details) {
            $cred_inform = $transac_details->credit_inform;
    
            // Calculate overall total charge, total downpayment, balance, and status
            $overallTotalCharge = $cred_inform->charge;
            $overallTotalDownpayment = $cred_inform->total_downpayment;
            $overallBalance = $overallTotalCharge - $overallTotalDownpayment;
            $overallStatus = $overallBalance == 0 ? 'Fully Paid' : 'Not Paid';
    
            $transac_data = [
                "transaction_details" => [
                    "transaction_details_id" => $transac_details->id,
                    "cred_inform_id" => $cred_inform->id,
                    "total_downpayment" => $overallTotalDownpayment,
                    "total_charge" => $overallTotalCharge,
                    "balance" => $overallBalance,
                    "status" => $overallStatus,
                ],   
            ];
    
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'sales' => $transac_data,
                'overall_total_charge' => $overallTotalCharge,
                'overall_total_downpayment' => $overallTotalDownpayment,
                'overall_balance' => $overallBalance,
                'overall_status' => $overallStatus,
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
