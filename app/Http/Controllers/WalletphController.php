<?php

namespace App\Http\Controllers;

use App\Models\Walletph;
use Illuminate\Http\Request;

class WalletphController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('welcome')->with('wallets', Walletph::where('id',2)->get());
        

         
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

       $data=Walletph::where('ph_id',$request->ph_id)->first();
       if($data){
       $fund=$data->funds;
       $x=$request->x;
       $funds=$fund+$x;
       
       Walletph::where('ph_id',$request->ph_id)->update([
         'funds'=>$funds,
        'ph_id'=>$request->ph_id,
        ]);
    }else{
        $x=$request->x;

        Walletph::create([
            'funds'=>$x,
           'ph_id'=>$request->ph_id,
        ]);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data=Walletph::where('ph_id',$request->ph_id)->get();
        return $data;
    }

    public function resetBalancePh(Request $request)
    {
    $ph_id = $request->input('ph_id');
    $pharmacy = Walletph::where('ph_id', $ph_id)->first();

    if ($pharmacy) {
        $pharmacy->update(['funds' => 0]);
        return response()->json(['message' => 'The balance has been successfully zeroed'], 200);
    } else {
        return response()->json(['message' => 'pharmacist not found'], 404);
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Walletph $walletph)
    {
        //
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Walletph $walletph)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Walletph $walletph)
    {
        //
    }
}
