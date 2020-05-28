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
    * Get the account that owns the hc.
    */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

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

    /**
     * Get the prescription record associated with the patient.
     */
    public function prescriptions()
    {
        return $this->hasMany('App\Prescription');
    }

//    public function getCreatedAtAttribute($value)
//    {
//        $date = date('Y-m-d h:i:s', strtotime($value) - 10800);
//
//        return $date;
//    }
//
//    public function getUpdatedAtAttribute($value)
//    {
//        $date = date('Y-m-d h:i:s', strtotime($value) - 10800);
//
//        return $date;
//    }

//    public function scopeDateWhere($query, $name, $operator = '=', $date)
//    {
//        $date = date('Y-m-d h:i:s', strtotime($date) + 10800);
//
//        $query->where($name, $operator, $date);
//    }
}
