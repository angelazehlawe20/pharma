<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Composition;
use App\Models\MedPharmacy;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class MedPharmacyController extends Controller
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
        //
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
    public function show(MedPharmacy $medPharmacy ,Request $request)
    {
        
        // $data=MedPharmacy::where('ph_id',$request->ph_id)->first();

       return MedPharmacy::join('medicines','medicines.id','=','med_pharmacies.med_id')
       ->join('categories','categories.id','=','medicines.category_id')
        ->where('ph_id',$request->ph_id)->select('med_pharmacies.id','medicines.name_med','medicines.image',
        'medicines.mg','medicines.exp','medicines.price_pharmacy',
        'med_pharmacies.quantity','medicines.price_customer','medicines.status','categories.name_category')->get();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function search(MedPharmacy $medPharmacy , Request $request)
    {   
        $data=Medicine::where('name_med',$request->name)->first();
        if(!$data){
       return MedPharmacy::join('medicines','medicines.id','=','med_pharmacies.med_id')
       ->where('pharmacies.city',$request->city)
       ->join('pharmacies','pharmacies.id','=','med_pharmacies.ph_id')
       ->where('medicines.name_med',$request->name)
       ->where('pharmacies.city',$request->city)
       ->select('pharmacies.name_ph','pharmacies.phone','pharmacies.city',
       'med_pharmacies.quantity','medicines.name_med','medicines.price_customer')
       ->get();
        
        }
        else if($data){
           $rows= Composition::where('med_id',$data->id)->get();

           foreach($rows as $row){
         $result=['medicine'=>$data,'compostion'=>$row->name_composition];

    //     return MedPharmacy::join('medicines','medicines.id','=','med_pharmacies.med_id')
    //    ->where('pharmacies.city',$request->city)
    //    ->join('pharmacies','pharmacies.id','=','med_pharmacies.ph_id')
    //    ->join('compositions','compositions.med_id','=','medicines.id')
    //    ->selectRaw('compositions.name_composition',[$row->name_composition])
    //    ->select('med_pharmacies.med_id','pharmacies.name_ph','pharmacies.phone','pharmacies.city',
    //    'med_pharmacies.quantity','medicines.name_med','medicines.price_customer','compositions.name_composition')
    //    ->get();
           }


        }
    }
public function notification(Request $request){
    $med =MedPharmacy::join('medicines','medicines.id','=','med_pharmacies.med_id')->
    where('med_pharmacies.ph_id',$request->id)->where('med_pharmacies.quantity','<',50)
    ->select('medicines.name_med','medicines.image','medicines.descrption','medicines.quantity','medicines.mg',
    'medicines.exp')->get();
     if($med){
        return $med ;
     }
  }
    

    public function exp(Request $request){

        $timestamp = now();
        $rows=MedPharmacy::join('medicines','medicines.id','=','med_pharmacies.med_id')->
        where('med_pharmacies.ph_id',$request->id)->whereYear('medicines.exp','=',$timestamp)
        ->select('medicines.name_med','medicines.image','medicines.descrption','medicines.mg','medicines.quantity',
        'medicines.exp')->get();
        
            if($rows){
               return response()->json($rows);
            }
        }
        
         
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedPharmacy $medPharmacy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedPharmacy $id , Request $request)
    {
        $medPharmacy = MedPharmacy::find($request->id);

    if (!$medPharmacy) {
        return response()->json(['message' => 'MedPharmacy not found'], 404);
    }

    $medPharmacy->delete();

    return response()->json(['message' => 'MedPharmacy deleted successfully']);
    }


    public function deductQuantity(Request $request)
{
    
   
    $medPharmacy = MedPharmacy::find($request->id);

    if (!$medPharmacy) {
        return response()->json(['message' => 'MedPharmacy not found'], 404);
    }

    $requestedQuantity = $request->input('requested_quantity');
    $currentQuantity = $medPharmacy->quantity;

    if ($requestedQuantity > $currentQuantity) {
        return response()->json(['message' => 'Insufficient quantity'], 400);
    }

    $remainingQuantity = $currentQuantity - $requestedQuantity;
    $medPharmacy->quantity = $remainingQuantity;
    $medPharmacy->save();

    return response()->json(['remaining_quantity' => $remainingQuantity]);
}
}
