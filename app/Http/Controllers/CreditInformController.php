<?php

namespace App\Http\Controllers;

use App\Models\credit_inform;
use App\Models\credit_users;
use Illuminate\Http\Request;

class CreditInformController extends Controller
{
    
    public function index(Request $request)
    {
        $credit_inform = credit_inform::query();
        $req = $request->keyword;
        if ($req) {
            $credit_inform->where('invoice_number', 'LIKE', '%' . $req . '%')
                ->orWhere('credit_date', 'LIKE', '%' . $req . '%')
                ->orWhere('status', 'LIKE', '%' . $req . '%')
                ->orWhere('charge', 'LIKE', '%' . $req . '%');
        }

        $credit_info = $credit_inform->paginate(10);

        if ($credit_info->count() > 0) {
            $CreditInformData = $credit_info->map(function ($cred_info) {
                return [
                    'credit_inform_id' => $cred_info->id,
                    'credit_users_id' => $cred_info->credit_users ? $cred_info->credit_users->credit_name : null ,
                    'downpayment_info_id' => $cred_info->downpayment_info ? $cred_info->downpayment_info : 'No Downpayment Data',
                    'credit_date' => $cred_info->credit_date,
                    'invoice_number' => $cred_info->invoice_number,
                    'charge' => $cred_info->charge,
                    'status' => $cred_info->status,
                ];
            });

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'credit_information' => $CreditInformData,
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

    public function searchCreditInform($credit_inform)
    {
        $cred_info = credit_inform::select('credit_inform.*')
            ->join('credit_users', 'credit_inform.credit_users_id', '=', 'credit_users.id')
            ->where('invoice_number', 'like', '%' . $credit_inform . '%')
            ->orWhere('credit_users.credit_name', 'like', '%' . $credit_inform . '%')
            ->get();

        if (empty(trim($credit_inform))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
            return response()->json($cred_info);
        }
    }

    public function storeCreditInform(Request $request, credit_inform $credit_inform)
    {
        $request->validate([
            'credit_users_id' => 'required|integer|digits_between:1, 999',
            'downpayment_info_id' => 'nullable|integer|digits_between:1, 999',
            'credit_date' => 'nullable|date|date_format:Y-m-d',
            'invoice_number' => 'required|string|max:255',
            'charge' => 'nullable|numeric|between:0,999999.99',
            'status' => 'nullable|string|max:255',
        ]);
    
        // Retrieve the credit_users record
        $creditUser = credit_users::find($request->credit_users_id);
    
        if (!$creditUser) {
            return response()->json([
                "status" => 404,
                "message" => "Credit User not found.",
            ], 404);
        }
    
        // Calculate the sum of charge for the credit_user
        $totalCharge = $creditUser->credit_inform()->sum('charge');
    
        // Check if the new charge exceeds the credit_limit
        if (($totalCharge + $request->charge) > $creditUser->credit_limit) {
            return response()->json([
                "status" => 400,
                "message" => "Charge exceeds the credit limit.",
                "total_charge" => $totalCharge
            ], 400);
        }
    
        $credit_inform = credit_inform::create($request->all());
    
        if ($credit_inform) {
            return response()->json([
                "status" => 200,
                "credit_inform" => [
                    'credit_inform_id' => $credit_inform->id ? $credit_inform->id : null,
                    'credit_users_id' => $credit_inform->credit_users ? $credit_inform->credit_users : null,
                    'downpayment_info_id' => $credit_inform->downpayment_info ? $credit_inform->downpayment_info : 'No Downpayment Data',
                    'credit_date' => $credit_inform->credit_date ? $credit_inform->credit_date : null,
                    'invoice_number' => $credit_inform->invoice_number ? $credit_inform->invoice_number : null,
                    'charge' => $credit_inform->charge ? $credit_inform->charge : null,
                    'status' => $credit_inform->status ? $credit_inform->status : null,
                ],
                "message" => "Added the Credit Information Successfully",
                "balance_charge" => $totalCharge
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Add a Credit Information",
            ]);
        }
    }

    public function showById($id)
    {
        $cred_inform = credit_inform::with(['credit_users', 'downpayment_info'])->find($id);

        if ($cred_inform) {
            $CreditInformData = [
                'credit_inform_id' => $cred_inform->id ? $cred_inform->id : null,
                'credit_users_id' => $cred_inform->credit_users ? $cred_inform->credit_users : null,
                'downpayment_info_id' => $cred_inform->downpayment_info ? $cred_inform->downpayment_info : null,
                'credit_date' => $cred_inform->credit_date,
                'invoice_number' => $cred_inform->invoice_number,
                'charge' => $cred_inform->charge,
                'status' => $cred_inform->status,
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $CreditInformData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }

    public function showCreditInform(credit_inform $credit_inform)
    {

        $credit_inform_que = $credit_inform->get();

        if ($credit_inform_que->count() > 0) {
            $CreditInformData = $credit_inform_que->map(function ($cred_inform) {
                return [
                    'credit_inform_id' => $cred_inform->id ? $cred_inform->id : null,
                    'credit_users_id' => $cred_inform->credit_users ? $cred_inform->credit_users : null,
                    'downpayment_info_id' => $cred_inform->downpayment_info ? $cred_inform->downpayment_info : null,
                    'credit_date' => $cred_inform->credit_date,
                    'invoice_number' => $cred_inform->invoice_number,
                    'charge' => $cred_inform->charge,
                    'status' => $cred_inform->status,
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $CreditInformData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }

    public function showSoftDeletedCredInform($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $SoftDeletedCreditInform = credit_inform::onlyTrashed()->get()->toArray();
            if (!empty($SoftDeletedCreditInform)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Credit Information Found",
                    "credit_inform" => $SoftDeletedCreditInform
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Credit Information Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            $activeCreditInform = credit_inform::all()->toArray();
            if (!empty($activeCreditInform)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Active Credit Information Found",
                    "credit_inform" => $activeCreditInform
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No ActiveCredit Information Found",
                ]);
            }
        }
    }

    public function updateCreditInform(Request $request, credit_inform $credit_inform)
    {
        $request->validate([
            'credit_users_id' => 'required|integer|digits_between:1, 999',
            'downpayment_info_id' => 'nullable|integer|digits_between:1, 999',
            'credit_date' => 'nullable|date|date_format:Y-m-d',
            'invoice_number' => 'required|string|max:255',
            'charge' => 'required|numeric|between:0,999999.99',
            'status' => 'required|string|max:255',
        ]);

        if ($credit_inform->update($request->all())) {
          
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Credit Information Successfully",
                "data" => $credit_inform,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Credit Information",
            ]);
        }
    }

    public function destroyCreditInform(credit_inform $credit_inform)
    {
        //
        if ($credit_inform->delete()) {

            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Credit Information Successfully",
                "data" => $credit_inform,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Credit Information",
            ]);
        }
    }

    // Soft Delete
    public function softdeleterecord($credit_inform)
    {

        $data = credit_inform::find($credit_inform);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Credit Information not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Credit Information Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }

    public function addDownpayment($amount)
    {
        // Eager load the downpayment_info relationship
        $this->load('downpayment_info');
    
        $remainingCharge = $this->charge - $this->downpayment_info->sum('downpayment');
    
        if ($amount > $remainingCharge) {
            return false; // Downpayment exceeds remaining charge
        }
    
        // Create a new downpayment
        $this->downpayment_info()->create([
            'downpayment' => $amount,
            'dp_date' => now(), // You may adjust the date as needed
        ]);
    
        return true; // Downpayment added successfully
    }

}
