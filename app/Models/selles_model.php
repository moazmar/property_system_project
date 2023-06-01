<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class selles_model extends Model
{
    protected $table='sells';
    protected $fillable=[
'users_id',
'id_buyer',
'property_special_id'




    ];
    use HasFactory;
}
