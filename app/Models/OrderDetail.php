<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
   protected $fillable=['order_id','medicine_id',
    'quantity',
    'price',
    ],
    $table='order_details';

    use HasFactory;

    public function order()
    {
        return $this->belongsToMany(Order::class,'order_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class,'medicine_id');
    }
}
