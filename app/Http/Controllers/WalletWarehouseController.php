<?php

namespace App\Http\Controllers;

use App\Models\WalletWarehouse;
use Illuminate\Http\Request;

class WalletWarehouseController extends Controller
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

    public function resetBalanceWarehouse(Request $request)
    {
    $warehouse_id = $request->input('warehouse_id');
    $warehouse = WalletWarehouse::where('warehouse_id', $warehouse_id)->first();

    if ($warehouse) {
        $warehouse->update(['funds' => 0]);
        return response()->json(['message' => 'The balance has been successfully zeroed'], 200);
    } else {
        return response()->json(['message' => 'Warehouse not found'], 404);
    }
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
    public function show(WalletWarehouse $walletWarehouse , Request $request)
    {
        return WalletWarehouse::where('warehouse_id',$request->id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WalletWarehouse $walletWarehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WalletWarehouse $walletWarehouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletWarehouse $walletWarehouse)
    {
        //
    }
}
