<?php

namespace App\Http\Controllers;

use App\Models\credit_users;
use Illuminate\Http\Request;

class CreditUsersController extends Controller
{
    public function index(Request $request)
{
    $credit_users = credit_users::query();
    
    if ($request->has('keyword')) {
        $keyword = $request->keyword;
        $credit_users->where('credit_name', 'LIKE', '%' . $keyword . '%');
    }
    
    $credit_users = $credit_users->paginate(10);
    
    if ($credit_users->count() > 0) {
        $CreditUsersData = $credit_users->map(function ($credit_user) {
            // Determine the status for this credit user
            $status = $credit_user->fully_paid ? 'Fully Paid' : 'Not Fully Paid';

            return [
                'id' => $credit_user->id,
                'credit_name' => $credit_user->credit_name ?? null,
                'credit_limit' => $credit_user->credit_limit,
                // 'status' => $status,
            ];
        });

        return response()->json([
            'status' => '200',
            'message' => 'Successfully retrieved Credit Users',
            'credit_users' => $CreditUsersData,
            'pagination' => [
                'current_page' => $credit_users->currentPage(),
                'total' => $credit_users->total(),
                'per_page' => $credit_users->perPage(),
            ]
        ]);
    } else {
        return response()->json([
            'status' => '401',
            'message' => 'Credit User is empty'
        ]);
    }
}

    public function storeCreditUsers(Request $request)
    {
        $request->validate([
            'credit_name' => 'required|string|max:255',
            'credit_limit' => 'nullable|numeric|between:0,999999.99',
          
        ]);
        $credit_names = credit_users::create($request->all());

        if ($credit_names) {
            return response()->json([
                "status" => 200,
                "credit_names" => [
                    "id" => $credit_names->id,
                    "credit_name" => $credit_names->credit_name,
                    'credit_limit' => $credit_names->credit_limit ? $credit_names->credit_limit : null,
                ],

                "message" => "Added the Credit Names Successfully",
            ]);
        } else {
            return response()->json([

                "status" => 401,
                "message" => "Failed to Add a Credit Names",
            ]);
        }
    }

    public function showById($id){
        
        $cred_user = credit_users::find($id);

        if($cred_user){
            $CreditUserData = [
                'id' => $cred_user->id,
                'credit_name' => $cred_user->credit_name, 
                'credit_limit' => $cred_user->credit_limit,
            ];
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'credit_users' => $CreditUserData,
            ]);
        }
      
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showCreditUsers(credit_users $credit_users)
    {
        $credit_user_que = $credit_users->paginate(10);

        if($credit_user_que -> count() > 0){
            $CreditUserData = $credit_user_que->map(function ($cred_user) {
                return [
                    'id' => $cred_user->id,
                    'credit_name' => $cred_user->credit_name, 
                    'credit_limit' => $cred_user->credit_limit,
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $CreditUserData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showSoftDeletedCreditUser($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedCreditUser = credit_users::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedCreditUser)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Credit User Data Found",
                    "credit_user" => $softDeletedCreditUser
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Credit User Data Found",
                ]);
            }
        } else {
            if ($id == 0) {
                // Display the non-deleted records
                $activeCreditUser = credit_users::all()->toArray();
                if (!empty($activeCreditUser)) {
                    return response()->json([
                        "status" => "200",
                        "message" => "Active Credit User Data Found",
                        "credit_user" => $activeCreditUser
                    ]);
                } else {
                    return response()->json([
                        "status" => "404",
                        "message" => "No Active Credit User Data Found",
                    ]);
                }
            }
        }
    }


    public function updateCreditUser(Request $request, credit_users $credit_users)
    {

        $request->validate([
            'credit_name' => 'required|string|max:255',
            'credit_limit' => 'required|numeric|between:0,999999.99',
        ]);

        if ($credit_users->update($request->all())) {
           
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Credit User Successfully.",
                "data" => $credit_users,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Credit User.",
            ]);
        }
    }

    // Search API
    public function searchCreditUser($credit_user)
    {

        $cred_u = credit_users::where('credit_name', 'like', '%' . $credit_user . '%')->get();

        if (empty(trim($credit_user))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
            return response()->json($cred_u);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCreditUser(credit_users $credit_user)
    {
        //
        if ($credit_user->delete()) {
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Credit User Successfully.",
                "data" => $credit_user,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Credit User.",
            ]);
        }
    }

    // Soft Delete
    public function softdeleterecord($credit_user)
    {

        $data = credit_users::find($credit_user);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Credit User not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Credit User Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }
}

