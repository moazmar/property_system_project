<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class property_special_model extends Model
{
    protected $table='property_special';
    protected $fillable=[
'location_id',
'users_id',
'typeofproperty',
'rent_or_sell',
'address',
'numberofRooms',
'image',
'video',
'descreption',
'price',
'monthlyRent',
'area',
'price_square_meter',
'rent_square_meter',




    ];

    protected $casts = [
        'image' => 'array',
    ];
    use HasFactory;
}
