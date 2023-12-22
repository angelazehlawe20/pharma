<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Usercontroller extends Controller
{
    public function register(Request $request)
    {
      # code...
      //validate
      $rules=[
        'name_user'=>'required|string',
        'email'=>'required|string|unique:users',
        'password'=>'required|string|min:6',
        'city_user'=>'required|string',
        'street_user'=>'required|string',
        'phone_user'=>'required|string',
      ];
      $validator = Validator::make($request->all(),$rules);
    
      if($validator->fails()){
        return response()->json($validator->errors(),400);
      }
    
      //create new user n user table
    
    
      $user = User::create([
        'name_user'=>$request->name_user,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
        'city_user'=>$request->city_user,
        'street_user'=>$request->street_user,
        'phone_user'=>$request->phone_user
        
      ]);
      $token =  $user->createToken('Personal Access Token')->plainTextToken;
      $response = ['user'=>$user,'token'=>$token];
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
  $user = User::where('email',$request->email)->first();
        // if user email found and password is correct
        if($user && Hash::check($request->password,$user->password))
        {
             $token = $user->createToken('personal Access Token')->plainTextToken;
             $response = ['usre'=>$user,'token'=>$token];
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
public function index()
{
  return User::all();
     
}

public function update(Request $request, User $user)
    {
        
      User::where('id',$request->id)->update([
        'name_user'=>$request->name_user,
         'email'=>$request->email,
         'password'=>Hash::make($request->password),
         'city_user'=>$request->city_user,
         'street_user'=>$request->street_user,
         'phone_user'=>$request->phone_user,
        ]);
    }

    public function destroy( Request $request)
    {
      User::where('id',$request->id)->delete();
    }


}
