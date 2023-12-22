<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderUserDetail extends Model
{
    protected $fillable=['order_productid','med_id','status','product_id','quantity','price'],
    $table='order_user_details';
    use HasFactory;

    public function medpharamcy(){
        return $this->BelongsTo(MedPharmacy::class,'med_id');

    }

    public function product(){
        return $this->BelongsTo(Product::class,'product_id');

    }

    public function orderproductied()
    {
        return $this->belongsToMany(ProductOrder::class,'order_productid');
    }

    public function descrption(){
        return $this->hasMany(Descrption::class);
    }
}
