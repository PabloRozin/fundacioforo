<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getCreatedAtAttribute($value)
    {
        $date = date('Y-m-d h:i:s', strtotime($value) - 10800);

        return $date;
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = date('Y-m-d h:i:s', strtotime($value) - 10800);

        return $date;
    }
}
