<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'date',
        'items_per_prescription',
        'name',
        'text',
        'prolonged_treatment',
    ];
    
    protected $hidden = [];

    protected $with = [
        'medicines'
    ];
    
    public function account()
    {
        return $this->belongsTo('App\Account');
    }
    
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    public function professional()
    {
        return $this->belongsTo('App\Professional');
    }
    
    public function medicines()
    {
        return $this->belongsToMany('App\Medicine');
    }

    public function scopeDateWhere($query, $name, $operator = '=', $date)
    {
        $date = date('Y-m-d h:i:s', strtotime($date));

        $query->where($name, $operator, $date);
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
}
