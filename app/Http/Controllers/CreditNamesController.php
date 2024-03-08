<?php

namespace App\Http\Controllers;

use App\Models\credit_info;
use App\Models\credit_names;
use Illuminate\Http\Request;

class CreditNamesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $credit_name_query = credit_names::query();

        $req = $request->keyword;
        if ($req) {
            $credit_name_query->where('credit_name', 'LIKE', '%' . $req . '%');
        }
        $credit_name_inform = $credit_name_query->paginate(10);

        if ($credit_name_inform->count() > 0) {
            $Credit_Name_Data = $credit_name_inform->map(function ($cred_name_inform) {
                return [
                    'credit_name_id' => $cred_name_inform->id,
                    'credit_name' => $cred_name_inform->credit_name,
                    'downpayment' => $cred_name_inform->downpayment,
                    'dp_date' => $cred_name_inform->dp_date,
                    'invoice_number'=>  $cred_name_inform->invoice_number,
                    'charge'=>  $cred_name_inform->charge,
                    'credit_limit'=>  $cred_name_inform->credit_limit,
                    'credit_date'=>  $cred_name_inform->credit_date,
                 
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'credit_names' => $Credit_Name_Data,
                'pagination' => [
                    'current_page' => $credit_name_inform->currentPage(),
                    'total' => $credit_name_inform->total(),
                    'per_page' => $credit_name_inform->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Credit Name is empty'
            ]);
        }
    }

 //  Search
 public function searchCredit_Name($credit_name)
 {
     $cred = credit_names::where('credit_name', 'like', '%' . $credit_name . '%')->get();

     if (empty(trim($credit_name))) {
         return response()->json([
             "status" => "204",
             "message" => "No Input is Provided for Search",
         ]);
     } else {
         return response()->json($cred);
     }
 }

 public function storeCreditNameInfo(Request $request, credit_names $cred_name)
 {
     $request->validate([
                    'credit_name' => 'required|string|max:255',
                    'dp_date' =>  'nullable|date|date_format:Y-m-d',
                    'invoice_number' => 'required|string|max:255',
                    'charge' => 'required|numeric|between:0,999999.99',
                    'credit_limit' => 'required|numeric|between:0,999999.99',
                    'credit_date' => 'required|date|date_format:Y-m-d',
     ]);

     $cred_name = credit_names::create($request->all());

     if (!$cred_name) {
         return response()->json([
             'status' => 500,
             'message' => 'Failed to Add the Credit Name'
         ]);
     } else {
         return response()->json([
             'status' => 200,
             'message' => 'Successfully Added the Credit Name',
             'credit_name' => [
                'credit_name_id' => $cred_name->id,
                     'credit_name' => $cred_name->credit_name,
                    'downpayment' => 0,
                    'dp_date' =>  $cred_name->dp_date,
                    'invoice_number'=>  $cred_name->invoice_number,
                    'charge'=>  $cred_name->charge,
                    'credit_limit'=>  $cred_name->credit_limit,
                    'credit_date'=>  $cred_name->credit_date,
                 
             ]
         ]);
     }
 }

 public function showById($id)
 {

     $cred_name = credit_names::with('credit_info')->find($id);

     if ($cred_name) {
         $CreditNameData = [
             'credit_name_id' => $cred_name->id,
             'credit_name' => $cred_name->credit_name, //Specifying to show only the cred_name Type Name
             'downpayment' => $cred_name->downpayment,
             'dp_date' => $cred_name->dp_date,
             'invoice_number'=>  $cred_name->invoice_number,
             'charge'=>  $cred_name->charge,
             'credit_limit'=>  $cred_name->credit_limit,
             'credit_date'=>  $cred_name->credit_date,
         ];

         return response()->json([
             'status' => '200',
             'message' => 'Current Datas',
             'products' => $CreditNameData,
         ]);
     } else {
         return response()->json([
             'status' => '401',
             'message' => 'Empty Data'
         ]);
     }
 }
    

 public function showCreditName(credit_names $credit_name)
 {

     $cred_name_que = $credit_name->get();

     if ($cred_name_que->count() > 0) {
         $CreditNameData = $cred_name_que->map(function ($cred_n) {
             return [
                 'credit_name_id' => $cred_n->id,
                 'credit_name' => $cred_n->credit_name,
                 'downpayment' => $cred_n->downpayment,
                 'dp_date' => $cred_n->dp_date,
                 'invoice_number'=>  $cred_n->invoice_number,
             'charge'=>  $cred_n->charge,
             'credit_limit'=>  $cred_n->credit_limit,
             'credit_date'=>  $cred_n->credit_date,
             ];
         });
         return response()->json([
             'status' => '200',
             'message' => 'Current Datas',
             'credit_name' => $CreditNameData,
         ]);
     } else {
         return response()->json([
             'status' => '401',
             'message' => 'Empty Data'
         ]);
     }
 }

 public function showSoftDeletedCreditName($id)
 {
     if ($id == 1) {
         // Display only the soft-deleted records
         $softDeletedCreditName = credit_names::onlyTrashed()->get()->toArray();
         if (!empty($softDeletedCreditName)) {
             return response()->json([
                 "status" => "200",
                 "message" => "Soft-deleted Credit Name Data Found",
                 "credit_name" => $softDeletedCreditName
             ]);
         } else {
             return response()->json([
                 "status" => "404",
                 "message" => "No Soft-deleted Credit Name Data Found",
             ]);
         }
     } else {
         // Display the non-deleted records
         $activeCreditName = credit_names::all()->toArray();
         if (!empty($activeCreditName)) {
             return response()->json([
                 "status" => "200",
                 "message" => "Active Credit Name Data Found",
                 "product" => $activeCreditName
             ]);
         } else {
             return response()->json([
                 "status" => "404",
                 "message" => "No Active Credit Name Data Found",
             ]);
         }
     }
 }
 public function updateCreditName(Request $request, credit_names $credit_name)
 {
     $request->validate([
         'credit_name' => 'required|string|max:255',
         'dp_date' => 'nullable|date|date_format:Y-m-d',
         'invoice_number' => 'required|string|max:255',
         'charge' => 'required|numeric|between:0,999999.99',
         'credit_limit' => 'required|numeric|between:0,999999.99',
         'credit_date' => 'required|date|date_format:Y-m-d',
     ]);
 
     // Retrieve the original credit_name data before the update
     $originalCreditName = $credit_name->fresh();
 
     // Exclude 'downpayment' from the update request
     $updateData = $request->except(['downpayment']);
 
     if ($credit_name->update($updateData)) {
         // Check if the credit_name remains the same
         if ($originalCreditName->credit_name == $request->input('credit_name')) {
             // Retrieve the new downpayment value from the request
             $newDownpayment = $request->input('downpayment', 0);
 
             // Calculate the total downpayment including the old value
             $totalDownpayment = $originalCreditName->downpayment + $newDownpayment;
 
             // Update the total_downpayment in the related credit_info records
             $credit_info_records = credit_info::where('credit_names_id', $credit_name->id)->get();
             foreach ($credit_info_records as $credit_info) {
                 $credit_info->total_downpayment = $totalDownpayment;
                 $credit_info->balance = $credit_info->total_charge - $totalDownpayment;
                 $credit_info->save();
             }
         }
 
         // Retrieve the updated credit_name data
         $updatedCreditName = $credit_name->fresh();
 
         return response()->json([
             'status' => 200,
             'message' => 'You Updated the Credit Name Successfully',
             'data' => $updatedCreditName,
         ]);
     } else {
         return response()->json([
             'status' => 401,
             'message' => 'Failed to Update the Credit Name',
         ]);
     }
 }

 public function addDownpayment(Request $request, credit_names $credit_name)
{
    $request->validate([
        'downpayment' => 'required|numeric|between:0,999999.99',
        'dp_date' => 'required|date|date_format:Y-m-d',
    ]);

    // Check if downpayment exceeds the charge
    $newDownpayment = $request->input('downpayment');
    $remainingCharge = $credit_name->charge - $credit_name->downpayment;

    if ($newDownpayment > $remainingCharge) {
        $remainingBalance = $credit_name->charge - $credit_name->downpayment;
        return response()->json([
            'status' => 400,
            'message' => 'Downpayment exceeds the remaining Charge',
            'remaining_charge' => $remainingBalance,
        ]);
    }

    // Update credit_names downpayment and dp_date
    $credit_name->downpayment += $newDownpayment;
    $credit_name->dp_date = $request->input('dp_date');
    $credit_name->save();

    // Create or update credit_info record
    $credit_info = $credit_name->credit_info ?? new credit_info();
    $credit_info->credit_names_id = $credit_name->id;
    $credit_info->total_charge = $credit_name->charge;
    $credit_info->total_downpayment += $newDownpayment;
    $credit_info->balance = $credit_info->total_charge - $credit_info->total_downpayment;

    // Update status based on balance
    $credit_info->status = ($credit_info->balance == 0) ? 'Fully Paid' : 'Not Fully Paid';

    $credit_info->save();
    $remainingBalance = $credit_name->charge - $credit_name->downpayment;

    // Check if fully paid in credit_names
    if ($credit_name->downpayment == $credit_name->charge) {
        return response()->json([
            'status' => 200,
            'message' => 'This person is fully paid for this credit.',
            'remaining_charge' => $remainingBalance,
            'credit_names_id' => $credit_info->credit_names,
            'credit_info' => [
                 'credit_info_id' => $credit_info->id,
                 'credit_names' => $credit_info->credit_names->credit_name,
                 'total_charge' => $credit_info->total_charge,
                 'total_downpayment' => $credit_info->total_downpayment,
                 'balance' => $credit_info->balance,
                 'status' => $credit_info->status,
                 'created_at' => $credit_info->created_at,
                 'updated_at' => $credit_info->updated_at,
                 'deleted_at' => $credit_info->deleted_at,
             ],
         ]);
    }

    // Check if fully paid in credit_info
    if ($credit_info->balance == 0) {
        return response()->json([
            'status' => 200,
            'message' => 'This person has already 0 balance.',
            'remaining_charge' => $remainingBalance,
            'credit_names_id' => $credit_info->credit_names,
            'credit_info' => [
                 'credit_info_id' => $credit_info->id,
                 'credit_names' => $credit_info->credit_names->credit_name,
                 'total_charge' => $credit_info->total_charge,
                 'total_downpayment' => $credit_info->total_downpayment,
                 'balance' => $credit_info->balance,
                 'status' => $credit_info->status,
                 'created_at' => $credit_info->created_at,
                 'updated_at' => $credit_info->updated_at,
                 'deleted_at' => $credit_info->deleted_at,
             ],
         ]);
    }
   
    return response()->json([
        'status' => 200,
        'message' => 'Downpayment added successfully',
        'remaining_charge' => $remainingBalance,
        'credit_names_id' => $credit_info->credit_names,
       'credit_info' => [
            'credit_info_id' => $credit_info->id,
            'credit_names' => $credit_info->credit_names->credit_name,
            'total_charge' => $credit_info->total_charge,
            'total_downpayment' => $credit_info->total_downpayment,
            'balance' => $credit_info->balance,
            'status' => $credit_info->status,
            'created_at' => $credit_info->created_at,
            'updated_at' => $credit_info->updated_at,
            'deleted_at' => $credit_info->deleted_at,
        ],
    ]);
}


 public function destroyCreditName(credit_names $credit_name)
 {
     //
     if ($credit_name->delete()) {

         return response()->json([
             "status" => 200,
             "message" => "You Deleted the Credit Name Successfully",
             "credit_name" => $credit_name,
         ]);
     } else {
         return response()->json([
             "status" => 401,
             "message" => "Failed to Delete the Credit Name",
         ]);
     }
 }

 // Soft Delete
 public function softdeleterecord($credit_name)
 {
     $data = credit_names::find($credit_name);
     if (!$data) {
         return response()->json(
             [
                 'status' => 404,
                 'message' => 'Credit Name not found',
             ]
         );
     }
     $data->delete();
     return response()->json(
         [
             'status' => 201,
             'message' => 'Credit Name Soft Deleted Successfully',
             'credit_name' => $data
         ]
     );
 }

}
