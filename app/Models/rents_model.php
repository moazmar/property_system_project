<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\property_special_model;
class rents_model extends Model
{
protected $table='rent';
protected $fillable=[
'users_id',
'id_rent_user',
'property_special_id'


];


    use HasFactory;
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function properties()
    {
        return $this->belongsTo(property_special_model::class);
    }
}
