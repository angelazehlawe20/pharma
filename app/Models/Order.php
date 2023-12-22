<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Order extends Model
{
    protected $fillable=['id_ph','id_warehouse','status_user','total_price'],
    $table='orders';

    use HasFactory;
    public function pharamcy ()
    {
        return $this->belongsTo(Pharmacy::class,'id_ph');
    }
    public function orderdetail()
    {
        return $this->belongsToMany(OrderDetail::class,);
    }

    public function  warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

}
