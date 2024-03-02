<?php

namespace App\Http\Controllers;

use App\Models\markup;
use Illuminate\Http\Request;

class MarkupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $markup_query = markup::query();
        $req = $request->keyword;
            if ($req) {
             $markup_query->where('markup_name', 'LIKE', '%' .$req.'%')
             ->orWhere('markup_rate', 'LIKE', '%' .$req.'%');
         }
 
         $markup = $markup_query->paginate(10);
 
         if($markup -> count() > 0){
             $MarkupData = $markup->map(function ($marks) {
                 return [
                     'id' => $marks->id,
                     'markup_name' => $marks->markup_name, 
                     'markup_rate' => $marks->markup_rate,
                    
                 ];
             });
 
             return response()->json([
                 'status' => '200',
                 'message' => 'Current Datas',
                 'markup' => $MarkupData,
                 'pagination' => [
                     'current_page' => $markup->currentPage(),
                     'total' => $markup->total(),
                     'per_page' => $markup->perPage(),
                 ]
             ]);
         } else {
             return response()->json([
                 'status' => '401',
                 'message' => 'Markup is empty'
             ]);
         }
 
      }
 
       //  Search
     public function searchMarkup($markup){
        $markups = markup::where('markup_name', 'like', '%'.$markup.'%')->get();
 
        if(empty(trim($markup))) {
         return response()->json([
             "status" => "204",
             "message" => "No Input is Provided for Search",
         ]);
     } else {
         return response()->json($markups);
     }
     }


    public function storeMarkup(Request $request, markup $markup)
    {
        //
        $request->validate([
            'markup_name' => 'required|string|max:255',
            'markup_rate' => 'required|numeric'
         
        ]);
        $markup = markup::create($request->all());
  
        if ($markup) {
            return response()->json([
                    "status" => 200,
                    "markup" => [
                        "id" => $markup->id,
                        "markup_name" => $markup->markup_name,
                        "markup_rate" => $markup->markup_rate,
                        
                    ],
                    "message" => "Added the Markup Successfully",
                ]);
        } else {
            return response()->json([
               
                "status" => 401,
                "message" => "Failed to Add a Markup",
            ]);
        }
    }


    
    public function showById($id){
        $markup = markup::find($id);
        if($markup){
            $markupData = [
                'id' => $markup->id,
                'markup_name' => $markup->markup_name, 
                'markup_rate' => $markup->markup_rate,
               
            ];

            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',   
                'markup' => $markupData,
            ]);
        }
      
        else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }


    public function showMarkup(markup $markup)
    {
        
        $markup_que = $markup->paginate(10);

        if($markup_que -> count() > 0){
            $MarkupData = $markup_que->map(function ($markups) {
                return [
                    'id' => $markups->id,
                    'markup_name' => $markups->markup_name, 
                    'markup_rate' => $markups->markup_rate,
                   
                ];
            });
            return response()->json([
                'status' => '200',
                'message' => 'Current Datas',
                'markup' => $MarkupData,
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Empty Data'
            ]);
        }

    }

    
    public function showSoftDeletedMarkup($id)
    {
        if ($id == 1) {
            // Display only the soft-deleted records
            $softDeletedMarkup = markup::onlyTrashed()->get()->toArray();
            if (!empty($softDeletedMarkup)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Soft-deleted Markup Data Found",
                    "product_type" => $softDeletedMarkup
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Soft-deleted Markup Data Found",
                ]);
            }
        } else {
            // Display the non-deleted records
            $activeMarkup = markup::all()->toArray();
            if (!empty($activeMarkup)) {
                return response()->json([
                    "status" => "200",
                    "message" => "Active Markup Data Found",
                    "markup" => $activeMarkup
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "No Active Markup Data Found",
                ]);
            }
        }
    }

    public function updateMarkup(Request $request, markup $markup)
    {
        $request->validate([
            'markup_name' => 'required|string|max:255',
            'markup_rate' => 'required|numeric'
          
        ]);

        if ($markup->update($request->all())) {
           
            return response()->json([
                'status' => 200,
                "message" => "You Updated the Markup Successfully",
                "data" => $markup,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Update the Markup",
            ]);
        }
    }

    public function destroyMarkup(markup $markup)
    {
        //
        if ($markup->delete()) {
            return response()->json([
                "status" => 200,
                "message" => "You Deleted the Markup Data Successfully",
                "data" => $markup,
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Failed to Delete the Markup Data",
            ]);
        }
    }

     // Soft Delete
     public function softdeleterecord($markup){

        $data = markup::find($markup);

        if(!$data){
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Markup Data not found',
                ]);
        }
        $data->delete();
        return response()->json(
            [
                'status' => 201,
                'message' => 'Markup Data Soft Deleted Successfully',
                'data' => $data
            ]);

    }
}
