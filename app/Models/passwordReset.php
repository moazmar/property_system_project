<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class passwordReset extends Model
{
    protected $table='password_resets';
    protected $fillable=[
        'email',
        'token'
    ];

    use HasFactory;
    protected $guarded=[];
    const UPDATED_AT=null;
}
