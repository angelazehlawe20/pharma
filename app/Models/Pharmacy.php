<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Order;


class Pharmacy extends Authenticatable
{
    protected $fillable = [
        'name',
        'name_ph',
        'email',
        'password',
        'city',
        'street',
        'phone',
    ],
    $table='pharmacies';
    use HasApiTokens, HasFactory, Notifiable;
     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function walletph()
    {
        return $this->belongsTo(Walletph::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function medpharmacy(){
        return $this->hasMany(MedPharmacy::class);
    }
}
