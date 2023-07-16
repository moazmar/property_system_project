<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favorate_model extends Model
{
    
    protected $table='favorate';
    protected $fillable=[
'users_id',
'property_special_id'




    ];
    use HasFactory;
}
