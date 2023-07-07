<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\state_model;
use App\Models\property_special_model;
class location_model extends Model
{
    protected $table='location';
    protected $fillable=[
'state_id',
'address'






    ];
    use HasFactory;
    public function state()
    {
        return $this->belongsTo(state_model::class);
    }
    public function properties()
    {
        return $this->hasMany(property_special_model::class);
    }
}
