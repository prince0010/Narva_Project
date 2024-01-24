<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ApiController extends Controller
{
// Methods

    // Register API (POST)
    // Request class and $request offset
        public function register(Request $request){
            // Data Validation 
            $request->validate([
                // Key value Pairs -> Column Names or the Body Parameters name of what we pass while we calling this register method
                // Parameters for the users table in database
                "name" => "required|string|max:255",
                // first rule is it must be an email and the 2nd rule is every users email must be unique in all of the users || unique:users | users is table name in database
                "email" =>"required|string|email|max:255|unique:users",
                "password" =>"required|confirmed|string|min:6"
            ]);

            // Create User and call it in the use App\Models\User;
            User::create([
                // Column name in the Database Table (users)
                // Name value is you will get in the $request inside of the parameters of register(Request $request)
                "name" => $request->name,
                "email" => $request->email,
                // Make a plain text to an encrypted one is from use Illuminate\Support\Facades\Hash
                "password" => Hash::make($request->password) 
            ]);

            return response()->json([
                "status" => 201,
                "message" => "User Created Successfully"
            ]);
        }
        

        
        // Login API (POST)
        public function login(Request $request){
            
            // Data Validation
            $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);

            // After passed an email value and password we will use the concept of Auth Facade -> Auth\Facade

            // Attempt Method || pass email address and password
            
            // Checking User Login
            // Once user access with these details it is going to return a true response | else False will return
            if(Auth::attempt([
                "email" => $request->email,
                "password"=> $request->password
             
            ]))
            {
                // User Data Exist
                // Auth Facade is scope Resolution Operator and the Method is User() and it will return a User() offset and inside of the User() offset it have all the user values
                $user = Auth::user();  
                // Authentication function alternative for Auth::user() -> auth()->user(); 

                // Authorized Token Value || That can use inside for the next apis like profile and logout
                // Inside of the method will have to pass the token name

                // $token = $user->createToken('myToken')->accessToken;
                $token = $request->user()->createToken('myToken')->accessToken; 

                return response()->json([
                    "status" => 200,
                    // "name" => $request->name ,
                    "message" => "User Logged In Sucessfully",
                    "token" => $token
                ]);

            } 
            else
            {   
                return response()->json([
                    "status" => 401,
                    "message" => "Login Credentials is not Valid"
                ]);
            };

            // Confirm User Login
            
        }

        // Protected Profile and Logout whit this middleware auth:api
        //Profile API (GET)
        public function profile(){
           $user = Auth::user();
            
           return response()->json([
                "status" => 401,
                "message" => "Profile Information",
                // Get the data from the user since the Auth::user() -> the user() method contains All the info of the User Data
                "data" => $user
           ]);
        }

        // Logout API
        public function logout(Request $request){

           $token = $request->user()->token();
           $token->revoke();
            return response()->json([
                "status" => 200,
                "message" => "User is Logged Out"
            ]);
        }

        // public function loginhg() 
        // { 
        //     $user = Auth::user();
        //      $success['token'] = $user->createToken('myApp')->accessToken; 
        //     } 
        //     public function logingh(Request $request)
        //      { $user = Auth::user();
        //          $token = $request->user()->createToken('token')->plainTextToken; 
        //         }
}
