<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function register(Request $request)
{
  # code...
  //validate
  $rules=[
    'name'=>'required|string',
    'email'=>'required|string|unique:users',
    'password'=>'required|string|min:6'
  ];
  $validator = Validator::make($request->all(),$rules);

  if($validator->fails()){
    return response()->json($validator->errors(),400);
  }

  //create new admin  table


  $admin = Admin::create([
    'name'=>$request->name,
    'email'=>$request->email,
    'password'=>Hash::make($request->password)
  ]);
  $token =  $admin->createToken('Personal Access Token')->plainTextToken;
  $response = ['admin'=>$admin,'token'=>$token];
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
  $admin = Admin::where('email',$request->email)->first();
        // if user email found and password is correct
        if($admin && Hash::check($request->password,$admin->password))
        {
             $token = $admin->createToken('personal Access Token')->plainTextToken;
             $response = ['admin'=>$admin,'token'=>$token];
             return response()->json($response,200);
  }
  $response = ['message'=>'Incorrect email or password'];
  return response()->json($response,400);
}
   

public function perform()
{
    Session::flush();
    
    Auth::logout();
    $response=['message'=>'logout'];
    return response()->json($response,200);
}
    public function update(Request $request, Admin $admin)
    {
        Admin::where('id',$request->id)->update([
          'name'=>$request->name,
          'email'=>$request->email,
          'password'=>Hash::make($request->password),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
