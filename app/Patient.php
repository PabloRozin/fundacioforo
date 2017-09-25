<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'system_id',
		'patient_firstname',
		'patient_lastname',
		'patient_document_number',
		'patient_document_type',
		'patient_gender',
		'patient_birthdate',
		'patient_nationality',
		'patient_phone',
		'patient_cellphone',
		'patient_state',
		'patient_email_1',
		'patient_email_2',
		'patient_email_3',
		'patient_medical_coverage',
		'patient_medical_coverage_plan',
		'patient_medical_coverage_number',
		'patient_studies',
		'patient_complete_studies',
		'patient_ocupation',
		'patient_civil_status',
		'consultant_firstname',
		'consultant_lastname',
		'consultant_relationship',
		'consultant_street',
		'consultant_number',
		'consultant_flat',
		'consultant_city',
		'consultant_district',
		'consultant_postal_code',
		'significant_firstname_1',
		'significant_firstname_2',
		'significant_firstname_3',
		'significant_lastname_1',
		'significant_lastname_2',
		'significant_lastname_3',
		'significant_cellphone_1',
		'significant_cellphone_2',
		'significant_cellphone_3',
		'significant_phone_1',
		'significant_phone_2',
		'significant_phone_3',
		'significant_link_1',
		'significant_link_2',
		'significant_link_3',
		'derivative_firstname',
		'derivative_lastname',
		'derivative_cellphone',
		'derivative_phone',
		'professional_name_1',
		'professional_name_2',
		'professional_name_3',
		'professional_cellphone_1',
		'professional_cellphone_2',
		'professional_cellphone_3',
		'professional_phone_1',
		'professional_phone_2',
		'professional_phone_3',
		'doctor_firstname_1',
		'doctor_firstname_2',
		'doctor_firstname_3',
		'doctor_lastname_1',
		'doctor_lastname_2',
		'doctor_lastname_3',
		'doctor_cellphone_1',
		'doctor_cellphone_2',
		'doctor_cellphone_3',
		'doctor_phone_1',
		'doctor_phone_2',
		'doctor_phone_3',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
     * Get the admision record associated with the patient.
     */
    public function admissions()
    {
        return $this->hasMany('App\PatientAdmision');
    }

    /**
     * Get the hc for the blog patient.
     */
    public function hcDates()
    {
        return $this->hasMany('App\HCDate');
    }

    /**
     * The professionals that belong to the patient.
     */
    public function professionals()
    {
        return $this->belongsToMany('App\Professional');
    }
}
