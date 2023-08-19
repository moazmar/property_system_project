<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inform_model extends Model
{
    protected $table='inform';
    protected $fillable=[
    'admin_id',
    'users_id',
    'type_of_informing',
    'property_special_id',
    'user_who_isinformed_about_Him'
    ];  
    use HasFactory;
}
