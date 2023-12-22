<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedPharmacy;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pharmacy;
use App\Models\Walletph;
use App\Models\WalletWarehouse;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
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
        $orderuser=Order::where('id',$request->order_id)->first();
        $status=$orderuser->status;
        $status_user=$orderuser->status_user;
        if($status==0 & $status_user==0){
 //....add quantity...............
        $quantity=$request->quantity;

//....get med quantity............
        $data=Medicine::where('id',$request->medicine_id)->first();
        $oldquantity=$data->quantity;
//.....get price..................
        $price=$data->price_pharmacy*$request->quantity;
//.....get pharmacy fund..........
        $orderuser=Order::where('id',$request->order_id)->first();
        $id=$orderuser->id_ph;
       $wallet= Walletph::where('ph_id',$id)->first();

        $fund=$wallet->funds;

        $quantity=$oldquantity-$quantity;

        if($price>$fund){
            return response()->json(['message'=>'You do not have enough credit']);
    
        }elseif($request->quantity>$oldquantity){
            return response()->json(['message'=>'This quantity is not available']);
        }else{
        
        
     
//.....new med quantity............

        Medicine::where('id',$request->medicine_id)->update([
            'quantity'=>$quantity,
        ]);
//.....new fund for ph..................
       $fund=$fund-$price;

       Walletph::where('ph_id',$id)->update([
        'funds'=>$fund
       ]);
//......creat order...........................
     $result=  OrderDetail::create([
        'order_id'=>$request->order_id,
        'medicine_id'=>$request->medicine_id,
        'quantity'=>$request->quantity,
        'price'=>$price,
       ]);
       $totalprice=OrderDetail::where('order_id',$request->order_id)->sum('price');
       Order::where('id',$request->order_id)->update([
        'total_price'=>$totalprice
     ]);
     return response()->json(['order'=>$result]);

//..........add price to warehouse wallet.....
    //    $warehouse=WalletWarehouse::where('warehouse_id',$data->warehouse_id)->first();

    //    if($warehouse){
    //     $oldfunds=$warehouse->funds;
    //     $oldfunds=$oldfunds+$price;
    //     WalletWarehouse::where('warehouse_id',$data->warehouse_id)->update([
    //     'funds'=>$oldfunds,
    //     ]);}else{
    //         WalletWarehouse::where('warehouse_id',$data->warehouse_id)->create([
    //             'funds'=>$price,
    //             'warehouse_id'=>$data->warehouse_id,
    //         ]);

    //     }
       
    
    //.........add med to the pharmacy.........
        }

}else{
    return response()->json(['message'=>'you cant add order']);

}
    }


    /**
     * Display the specified resource.
     */
    public function showforwarehouse(Request $request)
    {


       return OrderDetail::join('medicines','medicines.id','=','order_details.medicine_id')
       ->join('orders','orders.id','=','order_details.order_id')
       ->join('pharmacies','pharmacies.id','=','orders.id_ph')
       ->where('order_details.order_id',$request->id)
        ->select('order_details.id','medicines.name_med','medicines.image','medicines.mg','medicines.exp',
        'medicines.descrption','medicines.price_pharmacy','medicines.price_customer',
        'order_details.quantity',
        'medicines.status',
        'pharmacies.name_ph','pharmacies.city','pharmacies.street','pharmacies.phone')->get();
             
    }


    public function showforpharmacy(Request $request)
    {


       return OrderDetail::join('medicines','medicines.id','=','order_details.medicine_id')
       ->join('orders','orders.id','=','order_details.order_id')
       ->join('warehouses','warehouses.id','=','orders.id_warehouse')->select('warehouses.name')
       ->join('categories','categories.id','=','medicines.category_id')
       ->where('order_details.order_id',$request->id)
        ->select('order_details.id','warehouses.name_warehouse','warehouses.city_warehouse','warehouses.street_warehouse',
        'warehouses.phone_warehouse',
        'medicines.exp','medicines.name_med','medicines.image','medicines.mg','medicines.exp',
        'medicines.price_pharmacy','medicines.price_customer',
        'medicines.status','order_details.quantity','order_details.price',
        'categories.name_category')->get();
             
    }

   
    public function edit(OrderDetail $orderDetail)
    {
        //
    }

    function accepted(Request $request){
       $order=Order::where('id',$request->id)->first();
       if($order->status_user==1){
        $status=$order->status;
        if($status==0){
            Order::where('id',$request->id)->update([
                'status'=>1
            ]);
           $data=OrderDetail::where('order_id',$order->id)->get();
           foreach($data as $result){
            $med=MedPharmacy::where('ph_id',$order->id_ph)->where('med_id',$result->medicine_id)->first();
            if($med){
                // $id=$med->med_id;
              $quantity=OrderDetail::where('medicine_id',$result->medicine_id)->sum('quantity');
              MedPharmacy::where('ph_id',$order->id_ph)->where('med_id',$result->medicine_id)->update([
                'quantity'=>$quantity
              ]);
            //   if(!$id){
            //     MedPharmacy::create([
            //         'ph_id'=>$order->id_ph,
            //         'med_id'=>$result->medicine_id,
            //         'price'=>$result->price,
            //         'quantity'=>$result->quantity,
            //         'image'=>'nsslck'
            //     ]);
            //   }
            }else{
                MedPharmacy::create([
                'ph_id'=>$order->id_ph,
                'med_id'=>$result->medicine_id,
                'quantity'=>$result->quantity,
                'image'=>'nsslck'
            ]);
            }
            // MedPharmacy::create([
            //     'ph_id'=>$order->id_ph,
            //     'med_id'=>$result->medicine_id,
            //     'price'=>$result->price,
            //     'quantity'=>$result->quantity,
            //     'image'=>'nsslck'
            // ]);



           }

           $totalprice=$order->total_price;
           $warehouse=$order->id_warehouse;


           $wallet= WalletWarehouse::where('warehouse_id',$warehouse)->first();
           if($wallet){
            $funds=$wallet->funds;
            $funds=$funds+$totalprice;
            WalletWarehouse::where('warehouse_id',$warehouse)->update([
                'funds'=>$funds,
            ]);
           }else{
            // $fund=$wallet->funds;
            // $fund=$totalprice+$fund;
            WalletWarehouse::where('warehouse_id',$warehouse)->create([
                'funds'=>$totalprice,
                'warehouse_id'=>$warehouse,
            ]);
           }
           return response()->json(['message'=>'you accept this order']);

        }else{
            Order::where('id',$request->id)->update([
                'status'=>0
            ]);
        }
        
    }else{
            return response()->json(['message'=>'you cant accept this order']);
    }
}
    
    public function update(Request $request, OrderDetail $orderDetail)
    {
        //
    }

    
    public function destroy(OrderDetail $orderDetail , Request $request)
    {
        // $order=Order::where('id',$request->id)->first();
        // $id=$order->id_warehouse;
       $data = OrderDetail::where('id',$request->id)->first();
        $quantity=$data->quantity;
         $price=$data->price;
        $order_id=$data->order_id;
       $order= Order::where('id',$order_id)->first();
        $status=$order->status;
        $status_user=$order->status_user;
        if($status==0 & $status_user==0){
            $medicine_id=$data->medicine_id;
            $med = Medicine::where('id',$medicine_id)->first();
            $oldqantity=$med->quantity;
            $quantity=$quantity+$oldqantity;
            Medicine::where('id',$medicine_id)->update([
             'quantity'=>$quantity
            ]);
            OrderDetail::where('id',$request->id)->delete();

           $wallet = Walletph::where('ph_id',$order->id_ph)->first();
            $fund=$wallet->funds;
            $fund=$price+$fund;
            Walletph::where('ph_id',$order->id_ph)->update([
                'funds'=>$fund
            ]);

            $totalprice=$order->total_price;
            $totalprice=$totalprice-$price;
            Order::where('id',$order_id)->update([
                'total_price'=>$totalprice
            ]);
        }
       else{
        return "you cant delete this Order";
       }
    }
    
}
