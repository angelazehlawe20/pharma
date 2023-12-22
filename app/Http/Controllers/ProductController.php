<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();

    
    }

    public function quantity(Request $request)
    {
       $new=$request->new;
       $data= Product::where('id',$request->id)->first();
       $quantity=$data->quantity;
       $quantity=$new+$quantity;
       Product::where('id',$request->id)->update([
        'quantity'=>$quantity,
       ]);
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
        $request->validate([
            'name'=>'required|string',
            'price'=>'required|integer',
            'description'=>'required|string',
            'quantity'=>'required|integer',
        ]);
        Product::create([
            'pharmacy_id'=>$request->pharmacy_id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description,
            'images'=>$request->images,
            'quantity'=>$request->quantity,

        ]);
    }

    public function deductQuantity(Request $request , $id)
{
    $product = Product::find($request->id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $requestedQuantity = $request->input('requested_quantity');
    $currentQuantity = $product->quantity;

    if ($requestedQuantity > $currentQuantity) {
        return response()->json(['message' => 'Insufficient quantity'], 400);
    }

    $remainingQuantity = $currentQuantity - $requestedQuantity;
    $product->quantity = $remainingQuantity;
    $product->save();

    return response()->json(['remaining_quantity' => $remainingQuantity]);
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
         return Product::where('pharmacy_id',$request->id)->get();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Product::where('id',$request->id)->update([
            'name'=>$request->name,
            'price'=>$request->price,
           'description'=>$request->description,
           'images'=>$request->images
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Request $request)
    {
        Product::where('id',$request->id)->delete();

    }
}
