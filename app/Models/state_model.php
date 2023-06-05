<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\location_model;
class state_model extends Model
{
protected $table='state';
protected $fillable=[
'nameState',





];

    use HasFactory;

    public function location()
    {
        return $this->hasOne(location_model::class);
    }

}
