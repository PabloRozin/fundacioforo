<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'items_per_prescription',
        'medicine-1',
        'medicine-2',
        'medicine-3',
        'medicine-4',
        'medicine-5',
        'medicine-6',
        'medicine-7',
        'medicine-8',
        'medicine-9',
        'medicine-10',
        'medicine-11',
        'medicine-12',
        'medicine-13',
        'medicine-14',
        'medicine-15',
        'medicine-16',
        'medicine-17',
        'medicine-18',
        'medicine-19',
        'medicine-20',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
    * Get the account that owns the admission.
    */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    /**
    * Get the patient that owns the admission.
    */
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    /**
    * Get the professional that owns the admission.
    */
    public function professional()
    {
        return $this->belongsTo('App\Professional');
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

    public function scopeDateWhere($query, $name, $operator = '=', $date)
    {
        $date = date('Y-m-d h:i:s', strtotime($date));

        $query->where($name, $operator, $date);
    }
}
