<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=[
    'name',
    'price',
    'description',
    'images',
    'pharmacy_id',
    'quantity',
],
    $table='products';

    use HasFactory;

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'pharmacy_id');
    }
    public function orderuserdetail()
    {
        return $this->hasMany(OrderUserDetail::class);
    }

}
