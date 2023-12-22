<?php

namespace App\Http\Controllers;

use App\Models\composition;
use Illuminate\Http\Request;

class CompositionController extends Controller
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

        

        Composition::create([
            'med_id'=>$request->med_id,
            'name_composition'=>$request->name,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(composition $composition)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(composition $composition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, composition $composition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(composition $composition)
    {
        //
    }
}
