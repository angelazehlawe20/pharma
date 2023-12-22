<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedPharmacy;
use App\Models\OrderUserDetail;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Walletuser;
use Illuminate\Http\Request;

class OrderUserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     //.............show for pharmacy.....................
    public function index(Request $request)
    {   
       

    }

    // public function showforusers(Request $request)
    // {
    //   return OrderUserDetail::join('med_pharmacies','med_pharmacies.id','=','order_user_details.med_id')
    //   ->join('medicines','medicines.id','=','med_pharmacies.med_id')
    //   ->join('product_orders','product_orders.id','=','order_user_details.order_productid')
    //   ->join('users','users.id','=','product_orders.user_id')
    //   ->where('order_productid',$request->order_id)->select('medicines.name_med','medicines.image','medicines.mg',
    //   'medicines.exp','medicines.descrption','medicines.price_customer','order_user_details.status',
    //   'order_user_details.quantity','order_user_details.price','users.name_user','users.city_user',
    //   'users.street_user','users.phone_user')->get();

    // }

    public function OrderMedForUsers(Request $request)
    {
            // return OrderUserDetail::join('med_pharmacies','med_pharmacies.id','=','order_user_details.med_id')
            // ->join('medicines','medicines.id','=','med_pharmacies.med_id')
            // ->join('product_orders','product_orders.id','=','order_user_details.order_productid')
            // ->join('users','users.id','=','product_orders.user_id')
            // ->where('order_user_details.order_productid',$request->id)->select('medicines.name_med','medicines.image','medicines.mg',
            // 'medicines.exp','medicines.descrption','medicines.price_customer','order_user_details.status',
            // 'order_user_details.quantity','order_user_details.price','users.name_user','users.city_user',
            // 'users.street_user','users.phone_user')->get();

            return OrderUserDetail::join('med_pharmacies','med_pharmacies.id','=','order_user_details.med_id')
            ->join('medicines','medicines.id','=','med_pharmacies.med_id')
            ->join('product_orders','product_orders.id','=','order_user_details.order_productid')
            ->join('pharmacies','pharmacies.id','=','product_orders.ph_id') 
            ->where('order_user_details.order_productid',$request->id)->select('order_user_details.id',
            'medicines.name_med','medicines.image','medicines.mg',
            'medicines.descrption','medicines.price_customer','order_user_details.status',
            'order_user_details.quantity','order_user_details.price','product_orders.ph_id','pharmacies.name_ph','pharmacies.city',
            'pharmacies.street','pharmacies.phone')->get();
    }
    public function OrderproductForUsers(Request $request){

            return OrderUserDetail::join('products','products.id','=','order_user_details.product_id')
            ->join('product_orders','product_orders.id','=','order_user_details.order_productid')
            ->join('pharmacies','pharmacies.id','=','product_orders.ph_id')
            ->where('order_user_details.order_productid',$request->id)->select('order_user_details.id',
            'products.name','products.images',
            'products.description','products.price','order_user_details.status',
            'order_user_details.quantity','order_user_details.price','product_orders.ph_id','pharmacies.name_ph','pharmacies.city',
            'pharmacies.street','pharmacies.phone')->get();
          
        }

        public function OrderMedForPharmacy(Request $request)
        {

                return OrderUserDetail::join('med_pharmacies','med_pharmacies.id','=','order_user_details.med_id')
                ->join('medicines','medicines.id','=','med_pharmacies.med_id')
                ->join('product_orders','product_orders.id','=','order_user_details.order_productid')
                ->join('users','users.id','=','product_orders.user_id')
                ->where('order_user_details.order_productid',$request->id)->select('medicines.name_med','medicines.image','medicines.mg',
                'medicines.exp','medicines.descrption','medicines.price_customer','order_user_details.status',
                'order_user_details.quantity','order_user_details.price','users.name_user','users.city_user',
                'users.street_user','users.phone_user')->get();
        }

        public function OrderproductForPharmacy(Request $request){

            return OrderUserDetail::join('products','products.id','=','order_user_details.product_id')
            ->join('product_orders','product_orders.id','=','order_user_details.order_productid')
            ->join('users','users.id','=','product_orders.user_id')
            ->where('order_user_details.order_productid',$request->id)->select('order_user_details.id',
            'products.name','products.images',
            'products.description','products.price','order_user_details.status',
            'order_user_details.quantity','order_user_details.price','product_orders.user_id','users.name_user','users.city_user',
            'users.street_user','users.phone_user')->get();
          
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
    $order=ProductOrder::where('id',$request->order_id)->first();
    $status=$order->status;
    $status_user=$order->status_user;
    if($status==1 or $status_user==1 ){
        return response()->json(['message'=>'you cant add order']);
    }else{

      $data= MedPharmacy::where('id',$request->medid)->first();
     $quantity=$data->quantity;

     $medid=$data->id;


    $med=Medicine::where('id',$data->med_id)->first();
    $status=$med->status;

    $pricecustomer=$med->price_customer;


    $qantityrequest=$request->qantityrequest;
     $id=$order->id;

     $price=$pricecustomer*$qantityrequest;

     //.............wallet user..............

    $user=$order->user_id;
    $wallet=Walletuser::where('user_id',$user)->first();
    $fund=$wallet->funds;

    if($price>$fund){
        return response()->json(['message'=>'You do not have enough credit']);

    }elseif($quantity<$qantityrequest){
        return response()->json(['message'=>'This quantity is not available']);
    }
        elseif($quantity==0){
            return response()->json(['message'=>'there is no med']);

        }
    else{

          $result= OrderUserDetail::create([
                'order_productid'=>$id,
                'med_id'=>$medid,
                'status'=>$status,
                'quantity'=>$qantityrequest,
                'price'=>$price
            ]);

            //...........new fund for user............
            $fund=$fund-$pricecustomer;
            Walletuser::where('user_id',$user)->update([
                'funds'=>$fund
            ]);
            //...........new quantity..................
            $quantity=$quantity-$qantityrequest;

            MedPharmacy::where('id',$request->medid)->update([
                'quantity'=>$quantity
            ]);

          $totalprice= OrderUserDetail::where('order_productid',$request->order_id)->sum('price');
         $order->total_price= $totalprice;
         $order->save();
         return response()->json(['order'=>$result]);

        }
    }
    }

    /**
     * Display the specified resource.
     */
    public function accepteforph(OrderUserDetail $orderUserDetail , Request $request)
    {
      $data = OrderUserDetail::where('id',$request->id)->first();
      $status=$data->status;
      if($status==0){
        $data->update([
            'status'=>1
        ]);
      }else{
        $data->update([
            'status'=>0
        ]);
        return response()->json(['meesage'=>'The order has been confirmed']);
      }
    }



    public function storeproduct(Request $request)
    {
    $data= Product::where('id',$request->productid)->first();
     $quantity=$data->quantity;

     $productid=$data->id;


    $price=$data->price;


    $qantityrequest=$request->qantityrequest;
    $order=ProductOrder::where('id',$request->order_id)->first();
     $id=$order->id;

     $price=$price*$qantityrequest;

     //.............wallet user..............

    $user=$order->user_id;
    $wallet=Walletuser::where('user_id',$user)->first();
    $fund=$wallet->funds;

    if($price>$fund){
        return response()->json(['message'=>'You do not have enough credit']);

    }elseif($quantity<$qantityrequest){
        return response()->json(['message'=>'This quantity is not available']);
    }else{

          $result=  OrderUserDetail::create([
                'order_productid'=>$id,
                'status'=>0,
                'product_id'=>$productid,
                'quantity'=>$qantityrequest,
                'price'=>$price
            ]);

            //...........new fund for user............
            $fund=$fund-$price;
            Walletuser::where('user_id',$user)->update([
                'funds'=>$fund
            ]);
            //...........new quantity..................
            $quantity=$quantity-$qantityrequest;

            Product::where('id',$request->productid)->update([
                'quantity'=>$quantity
            ]);

          $totalprice= OrderUserDetail::where('order_productid',$request->order_id)->sum('price');
         $order->total_price= $totalprice;
         $order->save();

        return response()->json(['order'=>$result]);

        }

     



   


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderUserDetail $orderUserDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderUserDetail $orderUserDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderUserDetail $orderUserDetail , Request $request)
    {
       $data = OrderUserDetail::where('id',$request->id)->first();
       $orderid = $data->order_productid;
       $order = ProductOrder::where('id',$orderid)->first();
       $status=$order->status;
       $status_user=$order->status_user;

       if($status==0 & $status_user==0){
        if($data->med_id== null){
            $productid=$data->product_id;
           $product = Product::where('id',$productid)->first();
           $quantity=$product->quantity;
           $qantityorder=$data->quantity;
           $quantity=$quantity+$qantityorder;
           $product->update([
            'quantity'=>$quantity
           ]);
           
           $user_id=$order->user_id;
        //    $user = User::where('id',$user_id)->first();
          $wallet= Walletuser::where('user_id',$user_id)->first();
           $fund = $wallet->funds;
           $price=$data->price;
           $fund=$fund+$price;
           $wallet->funds=$fund;
           $wallet->save();
           $totalprice=$order->total_price;
           $totalprice=$totalprice-$price;
           $order->total_price=$totalprice;
           $order->save();
           $data->delete();

        }elseif($data->product_id== null){

            $productid=$data->med_id;
            $product = MedPharmacy::where('id',$productid)->first();
            $quantity=$product->quantity;
            $qantityorder=$data->quantity;
            $quantity=$quantity+$qantityorder;
            $product->update([
             'quantity'=>$quantity
            ]);
            
            $user_id=$order->user_id;
         //    $user = User::where('id',$user_id)->first();
           $wallet= Walletuser::where('user_id',$user_id)->first();
            $fund = $wallet->funds;
            $price=$data->price;
            $fund=$fund+$price;
            $wallet->funds=$fund;
            $wallet->save();
            $totalprice=$order->total_price;
            $totalprice=$totalprice-$price;
            $order->total_price=$totalprice;
            $order->save();
 
            $data->delete();
        }


       }else{
        return 'errore';    
       }


    }
}
