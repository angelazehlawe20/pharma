<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedPharmacy extends Model
{
  protected $fillable=['ph_id','status','med_id','quantity','image'],
  $table='med_pharmacies'; 
    use HasFactory;

    public function pharmacy(){
        return $this->belongsTo(Pharmacy::class,'ph_id');
    }

    public function medicine(){
        return $this->belongsTo(Medicine::class,'med_id');
    }
}
