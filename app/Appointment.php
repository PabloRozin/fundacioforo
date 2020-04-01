<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'date',
		'patient_id',
		'professional_id',
		'account_id',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	* Get the account that owns the professional.
	*/
	public function account()
	{
		return $this->belongsTo('App\Account');
	}

    /**
     * The patients that belong to the professional.
     */
    public function patients()
    {
        return $this->belongsToMany('App\Patients');
    }

    /**
     * The patients that belong to the professional.
     */
    public function professionals()
    {
        return $this->belongsToMany('App\Professionals');
    }
}
