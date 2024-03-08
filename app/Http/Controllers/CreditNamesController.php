<?php

namespace App\Http\Controllers;

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
                    'credit_info_ID' => $cred_name_inform->credit_info_ID,
                    'downpayment' => $cred_name_inform->downpayment,
                    'DP_date' => $cred_name_inform->DP_date,
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
                    'credit_info_ID' =>'required|integer|digits_between:1, 999',
                    'downpayment' => 'required|numeric|between:0,999999.99',
                    'DP_date' =>  'required|date|date_format:Y-m-d',
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
                    'credit_info_ID' =>$cred_name->credit_info_ID,
                    'downpayment' => $cred_name->downpayment,
                    'DP_date' =>  $cred_name->DP_date,
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
             'credit_name' => $cred_name->prod_type, //Specifying to show only the cred_name Type Name
             'credit_info_ID' => $cred_name->supplier,
             'downpayment' => $cred_name->part_num,
             'DP_date' => $cred_name->part_name,
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
                 'credit_info_ID' => $cred_n->credit_info_ID,
                 'downpayment' => $cred_n->downpayment,
                 'DP_date' => $cred_n->DP_date,
                
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
 public function updateProduct(Request $request, credit_names $credit_name)
 {
     $request->validate([
        'credit_name' => 'required|string|max:255',
        'credit_info_ID' =>'required|integer|digits_between:1, 999',
        'downpayment' => 'required|numeric|between:0,999999.99',
        'DP_date' =>  'required|date|date_format:Y-m-d',
         
     ]);

     if ($credit_name->update($request->all())) {
         // return redirect()->route('credit_name.index')
         // ->with(response()->json([
         //     'status' => 200,
         //     "message" => "You Updated the Product Successfully",
         // ]));
         return response()->json([
             'status' => 200,
             "message" => "You Updated the Credit Name Successfully",
             "data" => $credit_name,
         ]);
     } else {
         return response()->json([
             "status" => 401,
             "message" => "Failed to Update the Credit Name",
         ]);
     }
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
