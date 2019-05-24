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

    /**
    * Get the account that owns the user.
    */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

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

    /**
     * The admisions that belong to the account.
     */
    public function professional()
    {
        return $this->hasOne('App\Professional');
    }

    /**
     * The admisions that belong to the account.
     */
    public function patient()
    {
        return $this->hasOne('App\Patient');
    }

    /**
     * The admisions that belong to the account.
     */
    public function appointment()
    {
        return $this->hasOne('App\Appointment');
    }
}
