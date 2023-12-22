<?php

namespace App\Http\Controllers;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function register(Request $request)
     {
       # code...
       //validate
       $rules=[
         'name_warehouse'=>'required|string',
         'email'=>'required|string|unique:users',
         'password'=>'required|string|min:6',
         'city_warehouse'=>'required|string',
         'street_warehouse'=>'required|string',
         'phone_warehouse'=>'required|string',
       ];
       $validator = Validator::make($request->all(),$rules);
     
       if($validator->fails()){
         return response()->json($validator->errors(),400);
       }
     
     
     
       $warehouse = Warehouse::create([
         'name_warehouse'=>$request->name_warehouse,
         'email'=>$request->email,
         'password'=>Hash::make($request->password),
         'city_warehouse'=>$request->city_warehouse,
         'street_warehouse'=>$request->street_warehouse,
         'phone_warehouse'=>$request->phone_warehouse,
       ]);

       $token =  $warehouse->createToken('Personal Access Token')->plainTextToken;
       $response = ['warehouse'=>$warehouse,'token'=>$token];
       return response()->json($response,200);
     }
     
     public function login(Request $request)
     {
       # code...
       $rules = [
         'email'=>'required',
         'password'=>'required|string'
       ];
       $request->validate($rules);
       //find user in user table
       $warehouse = Warehouse::where('email',$request->email)->first();
             // if user email found and password is correct
             if($warehouse && Hash::check($request->password,$warehouse->password))
             {
                  $token = $warehouse->createToken('personal Access Token')->plainTextToken;
                  $response = ['warehouse'=>$warehouse,'token'=>$token];
                  return response()->json($response,200);
       }
       $response = ['message'=>'Incorrect email or password'];
       return response()->json($response,400);
     }
        
     
     //..............loogout
     public function perform()
     {
         Session::flush();
         
         Auth::logout();
         $response=['message'=>'logout'];
         return response()->json($response,200);
     }

    public function index()
    {
      return Warehouse::all();
         
    }

    /**
     * Show the form for creating a new resource.
     */
    public function information(Request $request)
    {
       return  Warehouse::where('id',$request->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse , Request $request)
    {
      $data = Warehouse::where('city_warehouse',$request->city)->get();
      if($data){
        return $data;
      }else{
        response()->json(['message'=>'Warehouse not found']);
      }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        
        Warehouse::where('id',$request->id)->update([
        'name_warehouse'=>$request->name_warehouse,
         'email'=>$request->email,
         'password'=>Hash::make($request->password),
         'city_warehouse'=>$request->city_warehouse,
         'street_warehouse'=>$request->street_warehouse,
         'phone_warehouse'=>$request->phone_warehouse,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Warehouse::where('id', $request->id)->delete();
        
    
    }
}
