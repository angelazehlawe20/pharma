<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $fillable=['total_price',
    'ph_id',
    'user_id',
    'status',
    'status_user'
    ],
    $table='product_orders';

    use HasFactory;

    public function user()
    {
        return $this->belongsToMany(User::class,'user_id');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class,'product_id');
    }

    public function orderuserdetail()
    {
        return $this->hasMany(OrderUserDetail::class);
    }

}
