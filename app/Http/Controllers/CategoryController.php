<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $data= Category::all();
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
       Category::create([
        'name_category'=>$request->name
       ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category , Request $request)
    {
        $data= Category::where('name_category',$request->category)->first();

        $id=$data->id;

       return Medicine::join('categories','categories.id','=','medicines.category_id')
        ->join('warehouses','warehouses.id','=','medicines.warehouse_id')
        ->where('categories.id',$id)->where('warehouses.id',$request->id)
        ->select('medicines.id','medicines.name_med','medicines.image',
        'medicines.mg',
        'medicines.exp','medicines.descrption','medicines.price_pharmacy',
        'medicines.price_customer','medicines.quantity',
        'medicines.status','categories.name_category')->get();
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , Category $category)
    {
        Category::where('id',$request->id)->update([
            'name_category'=>$request->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request , Category $category)
    {
        Category::where('id',$request->id)->delete();
    }
}
