<?php

namespace App\Http\Controllers;

use App\Models\interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $interest_query = interest::query();
        $req = $request->keyword;
            if ($req) {
             $interest_query->where('interest_name', 'LIKE', '%' .$req.'%')
             ->orWhere('interest_rate', 'LIKE', '%' .$req.'%');
         }
 
         $interest = $interest_query->paginate(10);
 
         if($interest -> count() > 0){
             $InterestData = $interest->map(function ($inter) {
                 return [
                     'id' => $inter->id,
                     'interest_name' => $inter->interest_name, 
                     'interest_rate' => $inter->interest_rate,
                    
                 ];
             });
 
             return response()->json([
                 'status' => '200',
                 'message' => 'Current Datas',
                 'interest' => $InterestData,
                 'pagination' => [
                     'current_page' => $interest->currentPage(),
                     'total' => $interest->total(),
                     'per_page' => $interest->perPage(),
                 ]
             ]);
         } else {
             return response()->json([
                 'status' => '401',
                 'message' => 'Interest is empty'
             ]);
         }
 
      }
 
       //  Search
     public function searchInterest($interest){
        $inter = interest::where('interest_name', 'like', '%'.$interest.'%')->get();
 
        if(empty(trim($interest))) {
         return response()->json([
             "status" => "204",
             "message" => "No Input is Provided for Search",
         ]);
     } else {
         return response()->json($inter);
     }
     }


    public function storeInterest(Request $request, interest $interest)
    {
        //
        $request->validate([
            'interest_name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric'
         
        ]);
        $interest = interest::create($request->all());
  
        if ($interest) {
            return response()->json([
                    "status" => 200,
                    "products" => [
                        "id" => $interest->id,
                        "interest_name" => $interest->interest_name,
                        "interest_rate" => $interest->interest_rate,
                        
                    ],
                    "message" => "Added the Interest Successfully",
                ]);
        } else {
            return response()->json([
               
                "status" => 401,
                "message" => "Failed to Add a Interest",
            ]);
        }
    }


    
    public function showById($id){
        $inter = interest::find($id);
        if($inter){
            $interestData = [
                'id' => $inter->id,
                'interest_name' => $inter->interest_name, 
                'interest_rate' => $inter->interest_rate,
               
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'interest' => $interestData,
            ]);
        }
      
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }


    public function showInterest(interest $interest)
    {
        
        $inter_que = $interest->paginate(10);

        if($inter_que -> count() > 0){
            $InterestData = $inter_que->map(function ($inter) {
                return [
                    'id' => $inter->id,
                    'interest_name' => $inter->interest_name, 
                    'interest_rate' => $inter->interest_rate,
                   
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'interest' => $InterestData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    
    public function showSoftDeletedInterest($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedInterest = interest::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedInterest)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Interest Data Found",
                    "product_type" => $softDeletedInterest
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Interest Data Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            $activeInterest = interest::all()->toArray();
            if (!empty($activeInterest)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Active Interest Data Found",
                    "interest" => $activeInterest
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Active Interest Data Found",
                ]);
            }
        }
    }

    public function updateInterest(Request $request, interest $interest)
    {
        $request->validate([
            'interest_name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric'
          
        ]);

        if ($interest->update($request->all())) {
           
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Interest Successfully",
                "data" => $interest,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Interest",
            ]);
        }
    }

    public function destroyInterest(interest $interest)
    {
        //
        if ($interest->delete()) {
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Interest Successfully",
                "data" => $interest,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Interest",
            ]);
        }
    }

     // Soft Delete
     public function softdeleterecord($interest){

        $data = interest::find($interest);

        if(!$data){
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Interest not found',
                ]);
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Interest Soft Deleted Successfully',
                'data' => $data
            ]);

    }
}
