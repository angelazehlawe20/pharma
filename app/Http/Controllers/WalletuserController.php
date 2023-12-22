<?php

namespace App\Http\Controllers;

use App\Models\Walletuser;
use Illuminate\Http\Request;

class WalletuserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data=Walletuser::where('user_id', $request->user_id)->get();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {




$data=Walletuser::where('user_id',$request->user_id)->first();

        if($data){
        $funds=$data->funds;

      $x=$request->x;

        $funds=$funds+$x;

      Walletuser::where('user_id',$request->user_id)->update([
            'funds'=>$funds,
            'user_id'=>$request->user_id,
        ]);
    }else{
       $x=$request->x;
        
        Walletuser::create([
          'funds'=>$x,
            'user_id'=>$request->user_id,
            
        ]);

    }

    }

    /**
     * Display the specified resource.
     */
    public function show(Walletuser $walletuser)
    {
        //
    }

    public function resetBalanceUser(Request $request)
    {
    $user_id = $request->input('user_id');
    $user = Walletuser::where('user_id', $user_id)->first();

    if ($user) {
        $user->update(['funds' => 0]);
        return response()->json(['message' => 'The balance has been successfully zeroed'], 200);
    } else {
        return response()->json(['message' => 'User not found'], 404);
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Walletuser $walletuser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Walletuser $walletuser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Walletuser $walletuser)
    {
        //
    }
}
