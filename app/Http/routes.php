<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

// Admin
Route::group(['middleware' => 'auth'], function ()
{
	Route::get('/', 'PatientController@index')->name('dashboard');

	Route::get('/patients/report/{patient_id?}', 'PatientController@report')->name('patients.report');

	Route::resource('patients', 'PatientController');

	Route::get('/patients/{patient_id}/admision', 'PatientController@index_admissions')->name('patients.admissions.index');
	Route::get('/patients/{patient_id}/admision/create', 'PatientController@create_admissions')->name('patients.admissions.create');
	Route::post('/patients/{patient_id}/admision', 'PatientController@store_admissions')->name('patients.admissions.store');
	Route::get('/patients/{patient_id}/admision/{admision_id?}', 'PatientController@show_admissions')->name('patients.admissions.show');

	Route::get('/patients/{patient_id}/hc', 'PatientController@index_hc')->name('patients.hc');
	Route::get('/patients/{patient_id}/hc/create', 'PatientController@create_hc')->name('patients.hc.create');
	Route::post('/patients/{patient_id}/hc', 'PatientController@store_hc')->name('patients.hc.store');

	Route::get('/patients/{patient_id}/assignProfessional', 'PatientController@assignProfessional')->name('patients.assignProfessional');
	Route::get('/patients/{patient_id}/unAssignProfessional', 'PatientController@unAssignProfessional')->name('patients.unAssignProfessional');

	Route::get('/professionals/report/{professional_id?}', 'ProfessionalController@report')->name('professionals.report');
	
	Route::resource('professionals', 'ProfessionalController');

	Route::resource('administrators', 'AdministratorController');
	
	Route::post('/file', 'FileController@store')->name('file.store');
});

Route::get('link', function() {
	Artisan::call('storage:link');
});