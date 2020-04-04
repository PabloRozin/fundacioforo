<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'document_number',
        'document_type',
        'cuit',
        'birthdate',
        'profession',
        'registration_number',
        'insurance',
        'email_1',
        'email_2',
        'email_3',
        'phone_1',
        'phone_info_1',
        'phone_2',
        'phone_info_2',
        'phone_3',
        'phone_info_3',
        'team',
        'state',
        'admision_date',
        'street_1',
        'street_2',
        'street_3',
        'number_1',
        'number_2',
        'number_3',
        'flat_1',
        'flat_2',
        'flat_3',
        'city_1',
        'city_2',
        'city_3',
        'district_1',
        'district_2',
        'district_3',
        'postal_code_1',
        'postal_code_2',
        'postal_code_3',
        'address_info_1',
        'address_info_2',
        'address_info_3',
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
     * Get the admision record associated with the patient.
     */
    public function admissions()
    {
        return $this->hasMany('App\PatientAdmission');
    }

    /**
     * Get the admision record associated with the patient.
     */
    public function prescriptions()
    {
        return $this->hasMany('App\Prescription');
    }

    /**
     * The patients that belong to the professional.
     */
    public function patients()
    {
        return $this->belongsToMany('App\Patient');
    }

    /**
     * The professionals that belong to the patient.
     */
    public function asignedPatients()
    {
        return $this->belongsToMany('App\Patient', 'asigned_patient_professional');
    }

    /**
     * The patients that belong to the professional.
     */
    public function appointments()
    {
        return $this->belongsToMany('App\Appointment');
    }

    /**
     * Get the hc for the blog professional.
     */
    public function hcDates()
    {
        return $this->hasMany('App\HCDate');
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

    /**
    * Get the professional user.
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
