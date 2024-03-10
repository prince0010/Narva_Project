<?php

namespace App\Http\Controllers;

use App\Models\downpayment_info;
use Illuminate\Http\Request;

class DownpaymentInfoController extends Controller
{
    public function index(Request $request)
    {
        $downpayment_query = downpayment_info::query();

        if ($request->keyword) {
            $downpayment_query->where('downpayment', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('dp_date', 'LIKE', '%' . $request->keyword . '%');
        }

        $downpayment = $downpayment_query->paginate(10);

        if ($downpayment->count() > 0) {
            $DownpaymentData = $downpayment->map(function ($dp) {
                return [
                    'downpayment_id' => $dp->id,
                    'downpayment' => $dp->downpayment ?? null,
                    'dp_date' => $dp->dp_date ?? null,
                ];
            });

            return response()->json([
                'status' => '200',
                'message' => 'successfully added Downpayment',
                'downpayment' => $DownpaymentData,
                'pagination' => [
                    'current_page' => $downpayment->currentPage(),
                    'total' => $downpayment->total(),
                    'per_page' => $downpayment->perPage(),
                ]
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Downpayment is empty'
            ]);
        }
    }

    public function storeDownpayment(Request $request)
    {
        $request->validate([
            'downpayment' => 'nullable|numeric|between:0,999999.99',
        'dp_date' => 'nullable|date|date_format:Y-m-d',
          
        ]);
        $downpayment = downpayment_info::create($request->all());

        if ($downpayment) {
            return response()->json([
                "status" => 200,
                "downpayment" => [
                    "downpayment_id" => $downpayment->id,
                    "downpayment" => $downpayment->downpayment ? $downpayment->downpayment : null,
                    // 'downpayment' => 0,
                    "dp_date" => $downpayment->dp_date ?  $downpayment->dp_date : null,
                ],

                "message" => "Added the downpayment Successfully",
            ]);
        } else {
            return response()->json([

                "status" => 401,
                "message" => "Failed to Add a downpayment",
            ]);
        }
    }

    public function showById($id){
        
        $downpayment = downpayment_info::find($id);

        if($downpayment){
            $DownpaymentData = [
                "downpayment_id" => $downpayment->id,
                    "downpayment" => $downpayment->downpayment,
                    "dp_date" => $downpayment->dp_date,
            ];
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'credit_users' => $DownpaymentData,
            ]);
        }
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showDownpayment(downpayment_info $downpayment)
    {
        $downpayment_que = $downpayment->paginate(10);

        if($downpayment_que -> count() > 0){
            $DownpaymentData = $downpayment_que->map(function ($dp) {
                return [
                    "downpayment_id" => $dp->id,
                    "downpayment" => $dp->downpayment,
                    "dp_date" => $dp->dp_date,
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'products' => $DownpaymentData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    public function showSoftDeletedDownpayment($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedDownpayment = downpayment_info::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedDownpayment)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Downpayment Data Found",
                    "downpayment" => $softDeletedDownpayment
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Downpayment Data Found",
                ]);
            }
        } else {
            if ($id == 0) {
                // Display the non-deleted records
                $activeDownpayment = downpayment_info::all()->toArray();
                if (!empty($activeDownpayment)) {
                    return response()->json([
                        "status" => "200",
                        "message" => "Active Downpayment Data Found",
                        "downpayment" => $activeDownpayment
                    ]);
                } else {
                    return response()->json([
                        "status" => "404",
                        "message" => "No Active Downpayment Data Found",
                    ]);
                }
            }
        }
    }


    public function addDownpayment(Request $request, downpayment_info $downpayment)
    {

        $request->validate([
            'downpayment' => 'required|numeric|between:0,999999.99',
            'dp_date' => 'required|date|date_format:Y-m-d',
        ]);

        if ($downpayment->update($request->all())) {
           
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Downpayment Successfully.",
                "data" => $downpayment,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Downpayment.",
            ]);
        }
    }

    // Search API
    public function searchDownpayment($downpayment)
    {

        $downp = downpayment_info::where('downpayment', 'LIKE', '%' . $downpayment . '%')
        ->orWhere('dp_date', 'LIKE', '%' . $downpayment . '%')->get();

        if (empty(trim($downpayment))) {
            return response()->json([
                "status" => "204",
                "message" => "No Input is Provided for Search",
            ]);
        } else {
            return response()->json($downp);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyDownpayment(downpayment_info $downpayment)
    {
        //
        if ($downpayment->delete()) {
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Downpayment Successfully.",
                "data" => $downpayment,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Downpayment.",
            ]);
        }
    }

    // Soft Delete
    public function softdeleterecord($downpayment)
    {

        $data = downpayment_info::find($downpayment);

        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Downpayment not found',
                ]
            );
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Downpayment Soft Deleted Successfully',
                'data' => $data
            ]
        );
    }
}
