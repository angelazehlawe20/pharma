<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pharmacy;
use App\Models\Walletph;
use App\Models\WalletWarehouse;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return $data=Order::where('status_user',0)->get();
    }

    public function Confirmedorders(Request $request)
    {
        return $data=Order::join('pharmacies','pharmacies.id','=','orders.id_ph')
        ->join('warehouses','warehouses.id','=','orders.id_warehouse')->
        where('orders.status_user',1)->where('orders.status',0)->select('orders.id','pharmacies.name_ph','pharmacies.city',
        'pharmacies.street','pharmacies.phone'
        ,'warehouses.name_warehouse','warehouses.city_warehouse','warehouses.street_warehouse'
        ,'warehouses.phone_warehouse',
        'orders.total_price','orders.status','orders.status_user','orders.created_at')->get();
    }

    public function done(Request $request)
    {
        return $data=Order::join('pharmacies','pharmacies.id','=','orders.id_ph')
        ->join('warehouses','warehouses.id','=','orders.id_warehouse')->
        where('orders.status_user',1)->where('orders.status',1)->select('orders.id','pharmacies.name_ph','pharmacies.city',
        'pharmacies.street','pharmacies.phone'
        ,'warehouses.name_warehouse','warehouses.city_warehouse','warehouses.street_warehouse'
        ,'warehouses.phone_warehouse',
        'orders.total_price','orders.status','orders.status_user','orders.created_at')->get();
    }

    public function store(Request $request)
    {   

       $orders= Order::create([
            'id_ph'=>$request->id_ph,
            'id_warehouse'=>$request->id_warehouse,
        ]);

       return response()->json(['orders'=>$orders]);

// //....add quantity...............
//         $quantity=$request->quantity;

// //....get med quantity............
//         $data=Medicine::where('id',$request->id_med)->first();
//         $oldquantity=$data->quantity;
// //.....get price..................
//         $price=$data->price_pharmacy*$request->quantity;
// //.....get pharmacy fund..........
//         $wallet= Walletph::where('ph_id',$request->id_ph)->first();
//         $fund=$wallet->funds;
     
//         if($quantity<=$oldquantity && $price<=$fund){
// //.....new med quantity............
//         $quantity=$oldquantity-$quantity;

//         Medicine::where('id',$request->id_med)->update([
//             'quantity'=>$quantity,
//         ]);
// //.....new fund for ph..................
//        $fund=$fund-$price;

//        Walletph::where('ph_id',$request->id_ph)->update([
//         'funds'=>$fund
//        ]);
// //......creat order...........................
//        Order::create([
//         'id_med'=>$request->id_med,
//         'id_ph'=>$request->id_ph,
//         'quantity'=>$request->quantity,
//         'total_price'=>$price,
//        ]);
// //..........add price to warehouse wallet.....
//        $warehouse=WalletWarehouse::where('warehouse_id',$data->warehouse_id)->first();

//        if($warehouse){
//         $oldfunds=$warehouse->funds;
//         $oldfunds=$oldfunds+$price;
//         WalletWarehouse::where('warehouse_id',$data->warehouse_id)->update([
//         'funds'=>$oldfunds,
//         ]);}else{
//             WalletWarehouse::where('warehouse_id',$data->warehouse_id)->create([
//                 'funds'=>$price,
//                 'warehouse_id'=>$data->warehouse_id,
//             ]);
//         }
       
//     //.........add med to the pharmacy.........
//     ;}else{
//         return("errore");
//     }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // $data=Medicine::where('warehouse_id',$request->id)->get();
        // foreach($data as $result){
        // $orders= OrderDetail::where('medicine_id',$result->id)->get();
        // foreach ($orders as $order){
        //   $results=Order::where('id',$order->order_id)->get();
        //   echo $results;
        // }
        //}


     return  Order::join('pharmacies','pharmacies.id','=','orders.id_ph')
       ->where('orders.id_warehouse',$request->id)->select('orders.id','orders.id_ph','pharmacies.name_ph','pharmacies.city',
       'pharmacies.street','pharmacies.phone','orders.status','orders.status_user','orders.total_price')->get();
        

    }
//...............show orders for pharmacy
    public function showorders(Request $request)
    {
        return  Order::join('warehouses','warehouses.id','=','orders.id_warehouse')
       ->where('orders.id_ph',$request->id)->select('orders.id','orders.id_warehouse','warehouses.name_warehouse',
       'warehouses.city_warehouse',
       'warehouses.street_warehouse','warehouses.phone_warehouse','orders.status_user','orders.total_price','orders.status','orders.created_at')->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function totalprice(Request $request, Order $order)
    {
       $data= Order::where('id',$request->id)->first();
       return $totalprice=$data->total_price;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order , Request $request)
    {
       $data = Order::where('id',$request->id)->first();
        $status=$data->status;
        $totalprice=$data->total_price;
        $status_user=$data->status_user;

       if($status==0 & $totalprice>0 & $status_user==0){ 

        $wallet=Walletph::where('ph_id',$data->id_ph)->first();
        $funds=$wallet->funds;
        $funds=$funds+$totalprice;
        Walletph::where('ph_id',$data->id_ph)->update([
            'funds'=>$funds
        ]);
         
      
             $rows= OrderDetail::where('order_id',$data->id)->get();

            foreach($rows as $row){
            $qantity=$row->quantity;

            $med=Medicine::where('id',$row->medicine_id)->first();
            $oldqantity=$med->quantity;
            $qantity=$qantity+$oldqantity;

            Medicine::where('id',$row->medicine_id)->update([
                'quantity'=>$qantity
                
            ]);

            OrderDetail::where('order_id',$data->id)->delete();
            Order::where('id',$request->id)->delete();

            }
        }elseif($totalprice==0){
            Order::where('id',$request->id)->delete();
        }else{
            response()->json(['message'=>'you cant delete this order']);
        }
           
        
      
       }

       public function acceptforpharmacy(Request $request){
        $data = Order::where('id',$request->id)->first();
            if($data->status_user==0){
                $data->status_user=1;
                $data->save();
              return  response()->json(['message' => 'Your order has been confirmed successfully']);
            }else{
                $data->status_user=0;
                $data->save();
                return  response()->json(['message' => 'Your order has been successfully cancelled']);
            
        }
       }
        
    

    }

