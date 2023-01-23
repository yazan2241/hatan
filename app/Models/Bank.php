<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'address_latitude',
        'address_longitude',
        'facebook_link',
        'instagrame_link',
    ];
}
