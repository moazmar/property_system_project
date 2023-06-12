<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\location_model;
use App\Models\rents_model;
use App\Models\selles_model;

class property_special_model extends Model
{
    protected $table='property_special';
    protected $guarded = ['id',];
//    protected $fillable=[
//'location_id',
//'users_id',
//'typeofproperty',
//'rent_or_sell',
//'address',
//'numberofRooms',
//'image',
//'video',
//'descreption',
//'price',
//'monthlyRent',
//'area',
//'price_square_meter',
//'rent_square_meter',
//
//
//
//
//    ];

    protected $casts = [
        'image' => 'array',
    ];
    use HasFactory;
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function location()
    {
        return $this->belongsTo(location_model::class);
    }
    public function rents()
    {
        return $this->hasMany(rents_model::class);
    }
    public function sells()
    {
        return $this->hasMany(selles_model::class);
    }
}
