<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletWarehouse extends Model
{
    protected $fillable=['funds','warehouse_id'],
    $table='wallet_warehouses';
    use HasFactory;
}
