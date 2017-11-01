<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HCDate extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'date',
		'file_1',
		'file_2',
		'file_3',
		'detail',
		'created_at',
	];

	protected $table = 'hc_dates';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * Get the patient that owns the hc.
	 */
	public function patient()
	{
	    return $this->belongsTo('App\Patient');
	}

	/**
	 * Get the professional that owns the hc.
	 */
	public function professional()
	{
	    return $this->belongsTo('App\Professional');
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
}
