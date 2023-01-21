<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'factory_name',
        'email',
        'password',
        'factory_owner',
        'factory_phone',
        'commercial_registration',
        'last_seen',
        'access_days',
        'status',
        'developer_id',
        'image'

    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function developer(){

        return $this->belongsTo(Developer::class,'developer_id','id');
    }
}


