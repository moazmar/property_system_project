<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rate_property_model extends Model
{
    protected $table='rate_property';
    protected $fillable=[
'users_id',
'userUseRate',
'rate'



    ];
    use HasFactory;
}
