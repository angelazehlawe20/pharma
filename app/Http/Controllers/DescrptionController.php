<?php

namespace App\Http\Controllers;

use App\Models\Descrption;
use App\Models\OrderUserDetail;
use Illuminate\Http\Request;

class DescrptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Descrption::create([
            'ordetal_id'=>$request->id,
            'description'=>$request->description,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Descrption $descrption , Request $request)
    {
       return Descrption::where('ordetal_id',$request->id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Descrption $descrption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Descrption $descrption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Descrption $descrption)
    {
        //
    }
}
