<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Admin_model extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $guard = 'Admin';
    protected $table='admin';
    protected $fillable=[
'name',
'email',
'password',
'phone',
'image',
'age',
'gender',
'information_about'

    ];
    

    protected $casts = [
            'image' => 'array'

    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    use HasFactory;
}
