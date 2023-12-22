<?php

namespace App\Http\Controllers;

use App\Models\MedPharmacy;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class PharmacyController extends Controller
{



    public function register(Request $request)
    {
      # code...
      //validate
      $rules=[
        'name'=>'required|string',
        'name_ph'=>'required|string',
        'email'=>'required|string|unique:users',
        'password'=>'required|string|min:6',
        'city'=>'required|string',
        'street'=>'required|string',
        'phone'=>'required|string',
      ];
      $validator = Validator::make($request->all(),$rules);
    
      if($validator->fails()){
        return response()->json($validator->errors(),400);
      }
    
      //create new user n user table
    
    
      $pharmacy = Pharmacy::create([
        'name'=>$request->name,
        'name_ph'=>$request->name_ph,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
        'city'=>$request->city,
        'street'=>$request->street,
        'phone'=>$request->phone
        
      ]);
      $token =  $pharmacy->createToken('Personal Access Token')->plainTextToken;
      $response = ['pharmacy'=>$pharmacy,'token'=>$token];
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
  $pharmacy = Pharmacy::where('email',$request->email)->first();
        // if user email found and password is correct
        if($pharmacy && Hash::check($request->password,$pharmacy->password))
        {
             $token = $pharmacy->createToken('personal Access Token')->plainTextToken;
             $response = ['pharmacy'=>$pharmacy,'token'=>$token];
             return response()->json($response,200);
  }
  $response = ['message'=>'Incorrect email or password'];
  return response()->json($response,400);
}
// logout///////////////
public function perform()
{
    Session::flush();
    
    Auth::logout();
    $response=['message'=>'logout'];
    return response()->json($response,200);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return pharmacy::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getmed(Request $request)
    {
       return $data = MedPharmacy::where('ph_id',$request->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
   
    public function show(Pharmacy $pharmacy , Request $request)
    {
       $data= Pharmacy::where('city',$request->city)->get();
       
        if($data){
          return $data;
        }else{
          return response()->json(['message' => 'pharmacy not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pharmacy $pharmacy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pharmacy $pharmacy)
    {
      pharmacy::where('id',$request->id)->update([
        'name'=>$request->name,
        "name_ph"=>$request->name_ph,
         'email'=>$request->email,
         'password'=>Hash::make($request->password),
         'city'=>$request->city,
         'street'=>$request->street,
         'phone'=>$request->phone,
        ]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request )
    {
      Pharmacy::where('id', $request->id)->delete();
        //
    }
}
