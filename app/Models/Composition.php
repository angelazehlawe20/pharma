<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    protected $fillable=['med_id','name_composition'],
    $table='compositions';
    use HasFactory;
    public function medicine(){
        return $this->belongsTo(Medicine::class,'med_id');
    }
}
