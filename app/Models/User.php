<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\property_special_model;
use App\Models\rents_model;
use App\Models\selles_model;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'age',
        'gender',
        'information_about'


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',

            'image' => 'array'

    ];
    public function properties()
    {
        return $this->hasMany(property_special_model::class);
    }
    public function rents()
    {
        return $this->hasMany(rents_model::class);
    }
    public function sells()
    {
        return $this->hasMany(selles_model::class);
    }
}
