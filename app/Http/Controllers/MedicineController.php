<?php

namespace App\Http\Controllers;

use App\Models\Composition;
use App\Models\medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
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
    public function createmed(Request $request)
    {
        
        //  $newimage=uniqid().'-'.$request->title.'.'.$request->image->extension();
        // $request->image->move(public_path('images'),$newimage);

        Medicine::create([
            'name_med'=>request('name_med'),
            'image'=>request('image'),
            'mg'=>request('mg'),
            'exp'=>request('exp'),
            'price_pharmacy'=>request('price_pharmacy'),
            'price_customer'=>request('price_customer'),
            'quantity'=>request('quantity'),
            'warehouse_id'=>request('warehouse_id'),
            'descrption'=>request('descrption'),
            'status'=>request('status'),
            'category_id'=>request('category_id'),


        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

      return  Medicine::join('warehouses','warehouses.id','=','medicines.warehouse_id')
        ->join('categories','categories.id','=','medicines.category_id')
        ->where('medicines.warehouse_id',$request->warehouse_id)
        ->select('medicines.id','medicines.name_med','medicines.image','medicines.mg','medicines.exp',
        'medicines.descrption',
        'medicines.price_pharmacy','medicines.price_customer','medicines.quantity','medicines.status',
        'categories.name_category')->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $quantity=$request->quantity;
        $data=Medicine::where('id',$request->id)->first();
        $oldquantity=$data->quantity;
         $quantity=$oldquantity+$quantity;
        Medicine::where('id',$request->id)->update([
         'quantity'=>$quantity
        ]);
    }

    public function notification(Request $request){
        $med =medicine::where('warehouse_id',$request->id)->where('quantity','<',50)->get();
         if($med){
            return response()->json($med);
         }
      }
    

    public function exp(Request $request){
        $timestamp = now();
         $med= medicine::where('warehouse_id',$request->id)->whereYear('exp','=',$timestamp)->get();
         if($med){
            return response()->json($med);
         }
      }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, medicine $medicine)
    {
        //$newimage=uniqid().'-'.$request->title.'.'.$request->image->extension();
       // $request->image->move(public_path('images'),$newimage);
        Medicine::where('id',$request->id)->update([
            'name_med'=>$request->name_med,
            'image'=>$request->image,
            'mg'=>$request->mg,
            'exp'=>$request->exp,
            'price_pharmacy'=>$request->price_pharmacy,
            'price_customer'=>$request->price_customer,
            'descrption'=>$request->descrption,
            'quantity'=>$request->quantity,
            'warehouse_id'=>$request->warehouse_id,
            'category_id'=>$request->category_id,
            'status'=>$request->status

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Medicine::where('id',$request->id)->delete();
    }
    public function test(Request $request){
        $med= Medicine::find($request->id);
        $composition = Composition::where('med_id',$med->id)->get(['name_composition']);


     $result=['id'=>$med->id,'name'=>$med->name_med,'composition'=>$composition];
      $result['composition'];
        
      return  Composition::whereIn('name_composition',$result['composition'])->get();
    }
    
}
