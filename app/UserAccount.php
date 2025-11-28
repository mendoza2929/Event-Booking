<?php

namespace App;  

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class UserAccount extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'users';  

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'api_token'
    ];

    protected $hidden = ['password', 'api_token'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->api_token = str_random(60);
        });
    }
}