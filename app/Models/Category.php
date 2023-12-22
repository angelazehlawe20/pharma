<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['name_category'],
    $table='categories';


    use HasFactory;

    public function Medicine()
    {
        return $this->hasMany(Medicine::class);
    }
}
