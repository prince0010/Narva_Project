<?php

namespace App\Http\Controllers;

use App\Models\credit_info;
use Illuminate\Http\Request;

class CreditInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $credit_query = credit_info::query();

        $req = $request->keyword;
        if ($req) {
            $credit_query->where('invoice_number', 'LIKE', '%' . $req . '%');
        }
        $credit_inform = $credit_query->paginate(10);

        if ($credit_inform->count() > 0) {
            $Credit_Data = $credit_inform->map(function ($cred_inform) {
                return [
                    'credit_info_id' => $cred_inform->id,
                    'credit_date' => $cred_inform->credit_date,
                    'invoice_number' => $cred_inform->invoice_number,
                    'charge' => $cred_inform->charge,
                    'credit_limit' => $cred_inform->credit_limit,
                    'balance' => $cred_inform->balance,
                    'status' => $cred_inform->status
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'credit_information' => $Credit_Data,
                'pagination' => [
                    'current_page' => $credit_inform->currentPage(),
                    'total' => $credit_inform->total(),
                    'per_page' => $credit_inform->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Credit Information is empty'
            ]);
        }
    }

    //  Search
    public function searchCredit_Info($credit_inform)
    {
        $cred = credit_info::where('invoice_number', 'like', '%' . $credit_inform . '%')->get();

        if (empty(trim($credit_inform))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
            return response()->json($cred);
        }
    }

    public function storeCreditInfo(Request $request)
    {
        $request->validate([
            'credit_date' => 'required|date|date_format:Y-m-d',
            'invoice_number' => 'required|string|max:255',
            'charge' => 'required|numeric|between:0,999999.99',
            'credit_limit' => 'required|numeric|between:0,999999.99',
            'balance' => 'required|numeric|between:0,999999.99',
            'status' => 'required|string|max:255',
        ]);

        $cred_info = credit_info::create($request->all());

        if (!$cred_info) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to Add the Credit Information'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Successfully Added the Credit Information',
                'credit_info' => [
                    'credit_info_id' => $cred_info->id,
                    'credit_date' => $cred_info->credit_date,
                    'invoice_number' => $cred_info->invoice_number,
                    'charge' => $cred_info->charge,
                    'credit_limit' => $cred_info->credit_limit,
                    'balance' => $cred_info->balance,
                    'status' => $cred_info->status
                ]
            ]);
        }
    }

    public function showById($id)
 {

     $cred_info = credit_info::find($id);

     if ($cred_info) {
         $CreditInfoData = [
            'credit_info_id' => $cred_info->id,
            'credit_date' => $cred_info->credit_date,
            'invoice_number' => $cred_info->invoice_number,
            'charge' => $cred_info->charge,
            'credit_limit' => $cred_info->credit_limit,
            'balance' => $cred_info->balance,
            'status' => $cred_info->status
         ];

         return response()->json([
             'status' => '200',
             'message' => 'Current Datas',
             'products' => $CreditInfoData,
         ]);
     } else {
         return response()->json([
             'status' => '401',
             'message' => 'Empty Data'
         ]);
     }
 }
    

    /**
     * Display the specified resource.
     */
    public function showCreditInfo(credit_info $credit_info)
    {
        $cred_info_data = $credit_info->get();

        if ($cred_info_data->count() > 0) {
            $Cred_Data = $cred_info_data->map(function ($credit_inform) {
                return [
                    'credit_info_id' => $credit_inform->id,
                    'credit_date' => $credit_inform->credit_date,
                    'invoice_number' => $credit_inform->invoice_number,
                    'charge' => $credit_inform->charge,
                    'credit_limit' => $credit_inform->credit_limit,
                    'balance' => $credit_inform->balance,
                    'status' => $credit_inform->status,
                    'created_at' => $credit_inform->created_at,
                    'updated_at' => $credit_inform->updated_at,
                    'deleted_at' => $credit_inform->deleted_at
                ];
            });
            return response()->json([
                'status' => 200,
                'message' => 'Current Datas',
                'credit_information' => $Cred_Data
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }
    }

    public function showSoftDeleteCredit($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedCredit = credit_info::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedCredit)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Credit Information Data Found",
                    "credit_information" => $softDeletedCredit
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Credit Information Data Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            if ($id == 0) {
                // Display the non-deleted records
                $activeCreditInfo = credit_info::all()->toArray();
                // If not Empty the activeCreditInfo
                if (!empty($activeCreditInfo)) {
                    return response()->json([
                        "status" => "200",
                        "message" => "Active Credit Information Data Found",
                        "product_type" => $activeCreditInfo
                    ]);
                } else {
                    return response()->json([
                        "status" => "404",
                        "message" => "No Active Credit Information Data Found",
                    ]);
                }
            }
        }
    }

    public function updateCreditInfo(Request $request, credit_info $credit_info)
    {
        $request->validate([
            'credit_date' => 'required|date|date_format:Y-m-d',
            'invoice_number' => 'required|string|max:255',
            'charge' => 'required|numeric|between:0,999999.99',
            'credit_limit' => 'required|numeric|between:0,999999.99',
            'balance' => 'required|numeric|between:0,999999.99',
            'status' => 'required|string|max:255',
        ]);

        if ($credit_info->update($request->all())) {
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Credit Information Successfully",
                "credit_info" => [
                    'credit_info_id' => $credit_inform->id,
                    'credit_date' => $credit_inform->credit_date,
                    'invoice_number' => $credit_inform->invoice_number,
                    'charge' => $credit_inform->charge,
                    'credit_limit' => $credit_inform->credit_limit,
                    'balance' => $credit_inform->balance,
                    'status' => $credit_inform->status,
                    'created_at' => $credit_inform->created_at,
                    'updated_at' => $credit_inform->updated_at,
                    'deleted_at' => $credit_inform->deleted_at
                ],
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Credit Information",
            ]);
        }
    }

    public function destroyCreditInfo(credit_info $credit_info)
    {
        //
        if ($credit_info->delete()) {
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Credit Information Successfully",
                "credit_info" => [
                    'credit_info_id' => $credit_inform->id,
                    'credit_date' => $credit_inform->credit_date,
                    'invoice_number' => $credit_inform->invoice_number,
                    'charge' => $credit_inform->charge,
                    'credit_limit' => $credit_inform->credit_limit,
                    'balance' => $credit_inform->balance,
                    'status' => $credit_inform->status,
                    'created_at' => $credit_inform->created_at,
                    'updated_at' => $credit_inform->updated_at,
                    'deleted_at' => $credit_inform->deleted_at
                ],
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Credit Information",
            ]);
        }
    }

    // Soft Delete
    public function softdeleterecord($credit_info)
    {

        $cred_info  = sales::find($credit_info);

        if (!$cred_info) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Credit Information not found',
                ]
            );
        }
        $cred_info->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Credit Information Soft Deleted Successfully',
                "credit_info" => [
                    'credit_info_id' => $credit_inform->id,
                    'credit_date' => $credit_inform->credit_date,
                    'invoice_number' => $credit_inform->invoice_number,
                    'charge' => $credit_inform->charge,
                    'credit_limit' => $credit_inform->credit_limit,
                    'balance' => $credit_inform->balance,
                    'status' => $credit_inform->status,
                    'created_at' => $credit_inform->created_at,
                    'updated_at' => $credit_inform->updated_at,
                    'deleted_at' => $credit_inform->deleted_at
                ],
            ]
        );
    }
}
