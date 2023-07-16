<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank_model extends Model
{
    
    protected $table='bank';
    protected $fillable=[
    'location_id',
    'name',
    'address'




    ];
    use HasFactory;
}
