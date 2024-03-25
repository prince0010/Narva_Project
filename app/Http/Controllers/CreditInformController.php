<?php

namespace App\Http\Controllers;

use App\Models\credit_inform;
use App\Models\credit_users;
use App\Models\downpayment_info;
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
                ->orWhere('charge', 'LIKE', '%' . $req . '%');
        }
    
        $credit_info = $credit_inform->paginate(10);
    
        if ($credit_info->count() > 0) {
            $CreditInformData = $credit_info->map(function ($cred_info) {
                $downpayment_info = $cred_info->downpayment_info->isNotEmpty() ? $cred_info->downpayment_info->toArray() : 'No Downpayment Data';
                return [
                    'credit_inform_id' => $cred_info->id,
                    'credit_users_id' => $cred_info->credit_users ? $cred_info->credit_users->credit_name : null ,
                    'downpayment_info' => $downpayment_info,
                    'credit_date' => $cred_info->credit_date,
                    'invoice_number' => $cred_info->invoice_number,
                    'charge' => $cred_info->charge,
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

//     public function storeCreditInform(Request $request)
// {
//     $request->validate([
//         'credit_users_id' => 'required|integer|digits_between:1,999',
//         'credit_date' => 'nullable|date|date_format:Y-m-d',
//         'invoice_number' => 'required|string|max:255',
//         'charge' => 'nullable|numeric|between:0,999999.99',
//         'status' => 'nullable|string|max:255',
//         'downpayment' => 'nullable|numeric|between:0,999999.99',
//         'dp_date' => 'nullable|date|date_format:Y-m-d',
//     ]);

//     // Retrieve the credit_users record
//     $creditUser = credit_users::find($request->credit_users_id);

//     if (!$creditUser) {
//         return response()->json([
//             "status" => 404,
//             "message" => "Credit User not found.",
//         ], 404);
//     }

//     // Calculate the sum of charge for the credit_user
//     $totalCharge = $creditUser->credit_inform()->sum('charge');

//     // Check if the new charge exceeds the credit_limit
//     if (($totalCharge + $request->charge) > $creditUser->credit_limit) {
//         return response()->json([
//             "status" => 400,
//             "message" => "Charge exceeds the credit limit.",
//             "total_charge" => $totalCharge
//         ], 400);
//     }

//     // Create the credit_inform
//     $creditInform = new credit_inform($request->all());

//     // Save the credit_inform to generate its ID
//     $creditInform->save();

//     // Automatically add downpayment information if provided
//     if ($request->has('downpayment') && $request->has('dp_date')) {
//         $downpayment = $request->input('downpayment');
//         $dpDate = $request->input('dp_date');

//         // Create downpayment_info
//         $downpaymentInfo = downpayment_info::create([
//             'downpayment' => $downpayment,
//             'dp_date' => $dpDate,
//         ]);

//         // Set downpayment_info_id for the credit_inform
//         $creditInform->downpayment_info_id = $downpaymentInfo->id;
//     }

//     // Save the credit_inform again to update the downpayment_info_id
//     $creditInform->save();

//     // Build the response
//     $response = [
//         "status" => 200,
//         "credit_users_id" => $creditUser->id,
//         "credit_name" => $creditUser->credit_name,
//         "credit_limit" => $creditUser->credit_limit,
//         "credit_inform" => [
//             'credit_inform_id' => $creditInform->id ?? null,
//             'credit_users_id' => $creditInform->credit_users_id ?? null,
//             'downpayment_info_id' => $creditInform->downpayment_info_id ?? 'No Downpayment Data',
//             'credit_date' => $creditInform->credit_date ?? null,
//             'invoice_number' => $creditInform->invoice_number ?? null,
//             'charge' => $creditInform->charge ?? null,
//             'status' => $creditInform->status ?? null,
//         ],
//         "downpayment_info" => [
//             'downpayment_id' => $downpaymentInfo->id ?? null,
//             'downpayment' => $downpaymentInfo->downpayment ?? null,
//             'dp_date' => $downpaymentInfo->dp_date ?? null,
//         ],
//         "message" => "Added the Credit Information Successfully",
//         "balance_charge" => $totalCharge + $request->charge
//     ];

//     return response()->json($response);
// }

//2nd to the NEW ONE
  // Method to store credit information
//   public function storeCreditInform(Request $request)
//   {
//       $request->validate([
//           'credit_users_id' => 'required|integer|digits_between:1,999',
//           'credit_date' => 'nullable|date|date_format:Y-m-d',
//           'invoice_number' => 'required|string|max:255',
//           'charge' => 'nullable|numeric|between:0,999999.99',
//           'status' => 'nullable|string|max:255',
//           'downpayment_info_id' => 'nullable|integer|digits_between:1,999',
//       ]);
  
//       // Retrieve the credit_users record
//       $creditUser = credit_users::find($request->credit_users_id);
  
//       if (!$creditUser) {
//           return response()->json([
//               "status" => 404,
//               "message" => "Credit User not found.",
//           ], 404);
//       }
  
//       // Calculate the sum of charge for the credit_user
//       $totalCharge = $creditUser->credit_inform()->sum('charge');
  
//       // Check if the new charge exceeds the credit_limit
//       if (($totalCharge + $request->charge) > $creditUser->credit_limit) {
//           return response()->json([
//               "status" => 400,
//               "message" => "Charge exceeds the credit limit.",
//               "total_charge" => $totalCharge
//           ], 400);
//       }
  
//       // Create the downpayment_info along with credit_inform
//       $downpaymentInfo = downpayment_info::create([
//           'downpayment' => null, // You can set default values if needed
//           'dp_date' => null,
//       ]);
  
//       $creditInform = new credit_inform($request->all());
//       $creditInform->downpayment_info_id = $downpaymentInfo->id;
//       $creditInform->save();
  
//       // Build the response
//       $response = [
//           "status" => 200,
//           "credit_users_id" => $creditUser->id,
//           "credit_name" => $creditUser->credit_name,
//           "credit_limit" => $creditUser->credit_limit,
//           "credit_inform" => [
//               'credit_inform_id' => $creditInform->id ?? null,
//               'credit_users_id' => $creditInform->credit_users_id ?? null,
//               'downpayment_info_id' => $creditInform->downpayment_info_id ?? 'No Downpayment Data',
//               'credit_date' => $creditInform->credit_date ?? null,
//               'invoice_number' => $creditInform->invoice_number ?? null,
//               'charge' => $creditInform->charge ?? null,
//               'status' => $creditInform->status ?? null,
//           ],
//           "downpayment_info" => [
//               'downpayment_id' => $downpaymentInfo->id ?? null,
//               'downpayment' => $downpaymentInfo->downpayment ?? null,
//               'dp_date' => $downpaymentInfo->dp_date ?? null,
//           ],
//           "message" => "Added the Credit Information Successfully",
//           "balance_charge" => $totalCharge + $request->charge
//       ];
  
//       return response()->json($response);
//   }
// Controller Method for Storing Credit Inform
public function storeCreditInform(Request $request)
{
    $request->validate([
        'credit_users_id' => 'required|integer|exists:credit_users,id',
        'credit_date' => 'nullable|date|date_format:Y-m-d',
        'invoice_number' => 'required|string|max:255',
        'charge' => 'nullable|numeric|between:0,999999.99',
    ]);

    // Retrieve the credit_users record
    $creditUser = credit_users::findOrFail($request->credit_users_id);

    // Calculate the total charge for the user
    $totalCharge = $creditUser->credit_inform()->sum('charge');

    // Check if the new charge exceeds the credit_limit
    if (($totalCharge + $request->charge) > $creditUser->credit_limit) {
        return response()->json([
            "status" => 400,
            "message" => "Charge exceeds the credit limit.",
            "total_charge" => $totalCharge
        ], 400);
    }

    // Create the credit_inform
    $creditInform = credit_inform::create([
        'credit_users_id' => $request->credit_users_id,
        'credit_date' => $request->credit_date,
        'invoice_number' => $request->invoice_number,
        'charge' => $request->charge,
    ]);

    // Build the response
    $response = [
        "status" => 200,
        "credit_users_id" => $creditUser->id,
        "credit_name" => $creditUser->credit_name,
        "credit_limit" => $creditUser->credit_limit,
        "credit_inform" => [
            'credit_inform_id' => $creditInform->id ?? null,
            'credit_users_id' => $creditInform->credit_users_id ?? null,
            'credit_date' => $creditInform->credit_date ?? null,
            'invoice_number' => $creditInform->invoice_number ?? null,
            'charge' => $creditInform->charge ?? null,
        ],
        "message" => "Added the Credit Information Successfully",
    ];

    return response()->json($response);
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

    public function deleteCredInform($id)
    {
        $creditInform = credit_inform::find($id);
    
        if ($creditInform) {
            $creditInform->delete();
            
            return response()->json([
                'status' => 200,
                'message' => 'Credit inform deleted successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => '404',
                'message' => 'Credit inform not found for the given ID.',
            ], 404);
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
    
    public function deleteCreditInformByCreditName($credit_name)
    {
        // Find the credit user by credit_name
        $creditUser = credit_users::where('credit_name', $credit_name)->first();
    
        if (!$creditUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credit user not found for the given credit name.',
            ], 404);
        }
    
        // Delete all CreditInform records associated with the found credit user
        $deletedCount = credit_inform::where('credit_users_id', $creditUser->id)->delete();
    
        // Delete the credit user record
        $creditUser->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => "Deleted {$deletedCount} credit informs and the credit user with credit name '{$credit_name}'.",
        ]);
    }

    public function updateCreditInform(Request $request, credit_inform $credit_inform)
    {
        $request->validate([
            'credit_users_id' => 'required|integer|digits_between:1, 999',
            'downpayment_info_id' => 'nullable|integer|digits_between:1, 999',
            'credit_date' => 'nullable|date|date_format:Y-m-d',
            'invoice_number' => 'required|string|max:255',
            'charge' => 'required|numeric|between:0,999999.99',
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

}
