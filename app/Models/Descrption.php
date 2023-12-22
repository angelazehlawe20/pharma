<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descrption extends Model
{
    protected $fillable=['ordetal_id','description'],
    $table='descrptions';
    use HasFactory;

    public function Orderdetail(){
        return $this->belongsTo(OrderUserDetail::class,'ordetal_id');
    }
}
