<?php

namespace App\Http\Controllers;

use App\Models\Descrption;
use App\Models\MedPharmacy;
use App\Models\OrderUserDetail;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Walletph;
use App\Models\Walletuser;
use Illuminate\Http\Request;
use League\CommonMark\Extension\DescriptionList\Node\Description;

class ProductOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function Confirmedorders(Request $request)
    {
        return $data=ProductOrder::join('pharmacies','pharmacies.id','=','product_orders.ph_id')
        ->join('users','users.id','=','product_orders.user_id')->
        where('product_orders.status_user',1)->where('product_orders.status',0)->select('product_orders.id','pharmacies.name_ph','pharmacies.city',
        'pharmacies.street','pharmacies.phone'
        ,'users.name_user','users.city_user','users.street_user'
        ,'users.phone_user',
        'product_orders.total_price','product_orders.status',
        'product_orders.status_user','product_orders.created_at')->get();
    }



    public function informedorders(Request $request)
    {
        return $data=ProductOrder::join('pharmacies','pharmacies.id','=','product_orders.ph_id')
        ->join('users','users.id','=','product_orders.user_id')->
        where('product_orders.status_user',1)->where('product_orders.status',1)->select('product_orders.id','pharmacies.name_ph','pharmacies.city',
        'pharmacies.street','pharmacies.phone'
        ,'users.name_user','users.city_user','users.street_user'
        ,'users.phone_user',
        'product_orders.total_price','product_orders.status','product_orders.status_user',
        'product_orders.created_at')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function accepteforadmin(Request $request)
    {
        $data=ProductOrder::where('id',$request->id)->first();
        $status=$data->status;
        $status_user=$data->status_user;
        $rows = OrderUserDetail::where('order_productid',$data->id)->sum('status');
        if($rows==0){
        if($status_user==1){
        if($status==0){
            $totalprice=$data->total_price;
            $phid=$data->ph_id;
           $wallet= Walletph::where('ph_id',$phid)->first();
           $fund=$wallet->funds;
           $newfun=$fund+$totalprice;
           $wallet->funds=$newfun;
           $wallet->save();
           $data->status=1;
           $data->save();
           return response()->json(['message'=>'you accept this order']);
        }
    }else{
        return response()->json(['message'=>'you cant accept this order']);
    }
}else{
    return response()->json(['message'=>'you cant accept this order beacause the pharmacy']);
}
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   


        $orders= ProductOrder::create([
            'ph_id'=>$request->id_ph,
            'user_id'=>$request->id_user,
        ]);

       return response()->json(['orders'=>$orders]);

        // $data=Product::where('id',$request->product_id)->first();
        // $quantity=$data->quantity;
        // $x=$request->x;
        // $quantity=$quantity-$x;
        // Product::where('id',$request->product_id)->update([
        // 'quantity'=>$quantity,
        // ]);
        // $price=$data->price;
        // $totalprice=$price*$x;

        // ProductOrder::create([
        //     'user_id'=>$request->id,
        //     'product_id'=>$request->product_id,
        //     'quantity'=>$x,
        //     'price'=>$totalprice,
        // ]);
        //  $wallet=Walletuser::where('user_id',$request->id)->first();
        // $funds=$wallet->funds;
        // $funds=$funds-$totalprice;
        //  Walletuser::where('user_id',$request->id)->update([
        //      'funds'=> $funds,
        //  ]);
         //...To Do send total price to wallet ph
    }

    public function showoforpharmacy(ProductOrder $productOrder , Request $request)
    {
        
     return  ProductOrder::join('users','users.id','=','product_orders.user_id')
     ->where('product_orders.ph_id',$request->id)->select('product_orders.id','users.name_user','users.city_user',
     'users.street_user','users.phone_user','product_orders.status','product_orders.status_user',
     'product_orders.total_price')->get();
      
    }


    public function showoforuser(ProductOrder $productOrder , Request $request)
    {
        
     return  ProductOrder::join('pharmacies','pharmacies.id','=','product_orders.ph_id')
     ->where('product_orders.user_id',$request->id)->select('product_orders.id','pharmacies.name_ph',
     'pharmacies.city',
     'pharmacies.street','pharmacies.phone','product_orders.status','product_orders.status_user',
     'product_orders.total_price')->get();
      
    }


    public function accepteforuser(ProductOrder $productOrder , Request $request)
    {
    $data=ProductOrder::where('id',$request->id)->first();
    $status=$data->status_user;
    if($status==0){
        $data->update([
            'status_user'=>1
        ]);
       return response()->json(['message'=>'The order has been confirmed']);
    }else{
        $data->update([
            'status_user'=>0
        ]);
        return  response()->json(['message' => 'Your order has been successfully cancelled']);
    }
    }

    public function update(Request $request, ProductOrder $productOrder)
    {

    }

    public function destroy(ProductOrder $productOrder , Request $request)
    {
        $data = ProductOrder::where('id',$request->id)->first();
        $status=$data->status;
        $status_user=$data->status_user;
        $total_price=$data->total_price;
        if($status==0 & $status_user==0 & $total_price>0){
              $rows = OrderUserDetail::where('order_productid',$data->id)->get();
              foreach($rows as $row){

                if($row->med_id== null){
                    $productid=$row->product_id;
                   $product = Product::where('id',$productid)->first();
                   $quantity=$product->quantity;
                   $qantityorder=$row->quantity;
                   $quantity=$quantity+$qantityorder;
                   $product->update([
                    'quantity'=>$quantity
                   ]);
                   
                   $user_id=$data->user_id;
                //    $user = User::where('id',$user_id)->first();
                  $wallet= Walletuser::where('user_id',$user_id)->first();
                   $fund = $wallet->funds;
                   $totalprice=$data->total_price;
                   $fund=$fund+$totalprice;
                   $wallet->funds=$fund;
                   $wallet->save();
                   OrderUserDetail::where('order_productid',$data->id)->delete();
                   $data->delete();

                }elseif($row->product_id== null){
        
                    $productid=$row->med_id;
                    $product = MedPharmacy::where('id',$productid)->first();
                    $quantity=$product->quantity;
                    $qantityorder=$row->quantity;
                    $quantity=$quantity+$qantityorder;
                    $product->update([
                     'quantity'=>$quantity
                    ]);
                    
                    $user_id=$data->user_id;
                 //    $user = User::where('id',$user_id)->first();
                   $wallet= Walletuser::where('user_id',$user_id)->first();
                    $fund = $wallet->funds;
                    $totalprice=$data->total_price;
                   $fund=$fund+$totalprice;
                   $wallet->funds=$fund;
                   $wallet->save();
                   Descrption::where('ordetal_id',$row->id)->delete();
                 $detail= OrderUserDetail::where('order_productid',$data->id)->delete();


                    $data->delete();
                }
        
        
               }
            }elseif($total_price==0){
                ProductOrder::where('id',$request->id)->delete();
            }else{
               return response()->json(['message'=>'you cant delete this order']);
            }
        }
}
