<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Medicine extends Model
{
    protected $fillable=['name_med','image','descrption','mg','exp','price_pharmacy','price_customer',
    'status','quantity','warehouse_id','category_id'],
    $table='medicines';
    use HasFactory;
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function orderdetail()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function medpharmacy(){
        return $this->hasMany(MedPharmacy::class);

    }
    public function composition(){
        return $this->hasMany(Composition::class,'id');

    }

    

}
