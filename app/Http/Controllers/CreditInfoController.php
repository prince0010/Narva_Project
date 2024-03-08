<?php

namespace App\Http\Controllers;

use App\Models\credit_info;
use App\Models\credit_names;
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
                    'total_charge' =>$cred_inform->total_charge,
                    'total_downpayment' =>$cred_inform->total_downpayment,
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
        $credData = credit_info::select('credit_info.*')
        ->join('credit_names', 'credit_info.credit_names_id', '=', 'credit_names.id')
        ->where('credit_names.credit_name', 'like', '%' . $credit_inform . '%')
        ->orWhere('invoice_number', 'like', '%' . $credit_inform . '%')
        ->get();

        if (empty(trim($credit_inform))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
           $response = [
                "status" => 200,
                "credit_info" => [],
            ];

            foreach ($credData as $cred) {
                $response['credit_info'][] = [
                    "credit_info_ID" => $cred->id,
                    "credit_name" => [
                        "credit_names_id" => $cred->credit_names->id,
                        "credit_name" => $cred->credit_names->credit_name,
                        "downpayment" => $cred->credit_names->downpayment,
                        "dp_date" => $cred->credit_names->dp_date,
                       
                    ],
                    'total_downpayment' =>$cred->total_downpayment,
                    'total_charge' =>$cred->total_charge,
                    'balance' => $cred->balance,
                    'status' => $cred->status
                ];
            }

            return response()->json($response);
        }
        }

        // public function storeCreditInfo(Request $request)
        // {
        //     $request->validate([
        //         'credit_date' => 'nullable|date|date_format:Y-m-d',
        //         'credit_names_id' => 'required|integer|digits_between:1,999',
        //         'status' => 'required|string|max:255',
        //     ]);
        
        //     $creditName = credit_names::findOrFail($request->input('credit_names_id'));
        
        //     // Retrieve all related records for the given credit_names_id
        //     $relatedCharges = credit_names::where('id', $request->input('credit_names_id'))->get();
        
        //     // Calculate the total charge by summing the 'charge' field of related records
        //     $totalCharge = $relatedCharges->sum('charge');
        
        //     // Calculate the total downpayment by summing the 'downpayment' field of related records
        //     $totalDownpayment = $relatedCharges->sum('downpayment');
        
        //     $balance = $totalCharge - $totalDownpayment;
        
        //     $credInfo = credit_info::create([
        //         'credit_date' => $request->input('credit_date'),
        //         'credit_names_id' => $request->input('credit_names_id'),
        //         'total_charge' => $totalCharge,
        //         'total_downpayment' => $totalDownpayment,
        //         'balance' => $balance,
        //         'status' => $request->input('status'),
        //     ]);
        
        //     if (!$credInfo) {
        //         return response()->json([
        //             'status' => 500,
        //             'message' => 'Failed to Add the Credit Information'
        //         ]);
        //     } else {
        //         return response()->json([
        //             'status' => 200,
        //             'message' => 'Successfully Added the Credit Information',
        //             'credit_info' => [
        //                 'credit_info_id' => $credInfo->id,
        //                 'credit_names_id' => $credInfo->credit_names,
        //                 'credit_date' => $credInfo->credit_date,
        //                 'total_charge' => $credInfo->total_charge,
        //                 'total_downpayment' => $credInfo->total_downpayment,
        //                 'balance' => $credInfo->balance,
        //                 'status' => $credInfo->status,
        //             ]
        //         ]);
        //     }
        // }

//         public function storeCreditInfo(Request $request)
// {
//     $request->validate([
//         'credit_date' => 'nullable|date|date_format:Y-m-d',
//         'credit_names_id' => 'required|integer|digits_between:1,999',
//         'status' => 'required|string|max:255',
//     ]);

//     $creditInfo = credit_info::create([
//         'credit_names_id' => $request->input('credit_names_id'),
//         'status' => $request->input('status'),
//     ]);

//     if (!$creditInfo) {
//         return response()->json([
//             'status' => 500,
//             'message' => 'Failed to Add the Credit Information'
//         ]);
//     }

//     return response()->json([
//         'status' => 200,
//         'message' => 'Successfully Added the Credit Information',
//         'credit_info' => [
//             'credit_info_id' => $creditInfo->id,
//             'credit_names_id' => $creditInfo->credit_names,
//             'total_charge' => $creditInfo->total_charge,
//             'total_downpayment' => $creditInfo->total_downpayment,
//             'balance' => $creditInfo->balance,
//             'status' => $creditInfo->status
//         ]
//     ]);
// }

    public function showById($id)
 {

     $cred_info = credit_info::find($id);

     if ($cred_info) {
         $CreditInfoData = [
            'credit_info_id' => $cred_info->id,
            'credit_names_id' => $cred_info->credit_names,
            'total_charge' =>$cred_info->total_charge,
            'total_downpayment' =>$cred_info->total_downpayment,
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
                    'credit_names_id' => $credit_inform->credit_names,
                    'total_charge' =>$credit_inform->total_charge,
                    'total_downpayment' => $credit_inform->total_downpayment,
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
            'credit_names_id'=>'required|integer|digits_between:1, 999',
            'total_charge' =>'required|numeric|between:0,999999.99',
            'total_downpayment' =>'required|numeric|between:0,999999.99',
            'balance' => 'required|numeric|between:0,999999.99',
            'status' => 'required|string|max:255',
        ]);

        if ($credit_info->update($request->all())) {
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Credit Information Successfully",
                "credit_info" => [
                    'credit_info_id' => $credit_inform->id,
                    'credit_names_id' => $credit_inform->credit_names_id,
                    'total_charge' =>$credit_inform->total_charge,
                    'total_downpayment' => $credit_inform->total_downpayment,
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

    public function destroyCreditInfo($credit_info)
    {

        $cred_info  = credit_info::find($credit_info);


        if (!$cred_info) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Credit Information not found',
                ]
            );
        }
       $cred_info->delete();
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Credit Information Successfully",
                "credit_info" => [
                    'credit_info_id' => $cred_info->id,
                    'credit_names_id' => $cred_info->credit_names_id,
                    'total_charge' =>$cred_info->total_charge,
                    'total_downpayment' => $cred_info->total_downpayment,
                    'balance' => $cred_info->balance,
                    'status' => $cred_info->status,
                    'created_at' => $cred_info->created_at,
                    'updated_at' => $cred_info->updated_at,
                    'deleted_at' => $cred_info->deleted_at
                ],
            ]);
      
    }

    // Soft Delete
    public function softdeleterecord($credit_info)
    {

        $cred_info  = credit_info::find($credit_info);

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
                    'credit_info_id' => $cred_info->id,
                    'credit_names_id' => $cred_info->credit_names_id,
                    'total_charge' =>$cred_info->total_charge,
                    'total_downpayment' => $cred_info->total_downpayment,
                    'balance' => $cred_info->balance,
                    'status' => $cred_info->status,
                    'created_at' => $cred_info->created_at,
                    'updated_at' => $cred_info->updated_at,
                    'deleted_at' => $cred_info->deleted_at
                ],
            ]
        );
    }
}
