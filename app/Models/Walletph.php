<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pharmacy;


class Walletph extends Model
{
        protected $fillable=['funds','ph_id'],
        $table='walletphs';
    use HasFactory;
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'ph_id');
    }
}
