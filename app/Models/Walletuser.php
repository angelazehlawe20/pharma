<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walletuser extends Model
{
    protected $fillable=['funds','user_id'],
    $table='walletusers';
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
