<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rents_model extends Model
{
protected $table='rent';
protected $fillable=[
'users_id',
'id_rent_user',
'property_special_id'


];


    use HasFactory;
}
