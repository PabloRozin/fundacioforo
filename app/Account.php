<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'professionals_limit',
		'patients_limit',
		'logo',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

    /**
     * The users that belong to the account.
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * The professionals that belong to the account.
     */
    public function professionals()
    {
        return $this->hasMany('App\Professional');
    }

    /**
     * The patients that belong to the account.
     */
    public function patients()
    {
        return $this->hasMany('App\Patient');
    }

    /**
     * The hc that belong to the account.
     */
    public function hcDates()
    {
        return $this->hasMany('App\HCDate');
    }

    /**
     * The admisions that belong to the account.
     */
    public function patientAdmisions()
    {
        return $this->hasMany('App\PatientAdmision');
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

    public function scopeDateWhere($query, $name, $operator = '=', $date)
	{
		$date = date('Y-m-d h:i:s', strtotime($date) + 10800);
		
	    $query->where($name, $operator, $date);
	}
}
