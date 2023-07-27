<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Account_bank extends Authenticatable
{
    
    protected $table='bank_account';
    protected $fillable=[
'users_id',
'bank_id',
'number_account',
'value_of_account'



    ];
    
    use HasFactory;
}
