<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location_model extends Model
{
    protected $table='location';
    protected $fillable=[
'state_id',
'address'






    ];
    use HasFactory;
}
