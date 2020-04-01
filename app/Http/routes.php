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
Route::group(
    ['middleware' => 'auth'],
    function () {
        Route::get(
            '/accept-conditions',
            function () {
                if (! in_array(Auth::user()->permissions, ['admin'])) {
                    $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

                    return redirect()->route('dashboard');
                }

                $data = [
                    'items' => [
                        'Acepto los Términos y condiciones de la aplicación web "Evolución Historia Clínica Digital"' => [
                            '' => [
                                'terms_and_conditions' => [
                                    'css_class' => 'col',
                                    'type' => 'showText',
                                    'title' => '',
                                    'content' => 'plainHtml.terms_and_conditions',
                                ],
                            ],
                        ],
                    ],
                    'back_url' => route('dashboard'),
                    'form_url' => route('accept_conditions'),
                    'form_method' => 'POST',
                    'title' => 'Términos y condiciones',
                    'button_text' => 'Aceptar'
                ];

                return view('form', $data);
            }
        )->name('accept_conditions');

        Route::post(
            '/accept-conditions',
            function () {
                if (! in_array(Auth::user()->permissions, ['admin'])) {
                    $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

                    return redirect()->route('dashboard');
                }

                $account = App\Account::where('id', Auth::user()->account_id)->firstOrFail();
                $account->accepted_conditions = 1;
                $account->save();

                Request::session()->flash('success', 'Los términos y condiciones fueron aceptados con éxito.');

                return redirect()->route('dashboard');
            }
        )->name('accept_conditions');

        Route::get('/', 'PatientController@index')->name('dashboard');

        // -------------------------------

        Route::get('/patients/report/{patient_id?}', 'PatientController@report')->name('patients.report');

        Route::resource('patients', 'PatientController');

        Route::get('/patients/{patient_id}/admissions', 'PatientController@index_admissions')->name('patients.admissions.index');
        Route::get('/patients/{patient_id}/admissions/create', 'PatientController@create_admissions')->name('patients.admissions.create');
        Route::post('/patients/{patient_id}/admissions', 'PatientController@store_admissions')->name('patients.admissions.store');
        Route::get('/patients/{patient_id}/admissions/{admision_id?}', 'PatientController@show_admissions')->name('patients.admissions.show');

        Route::get('/patients/{patient_id}/prescriptions', 'PatientController@index_prescriptions')->name('patients.prescriptions.index');
        Route::get('/patients/{patient_id}/prescriptions/create', 'PatientController@create_prescriptions')->name('patients.prescriptions.create');
        Route::post('/patients/{patient_id}/prescriptions', 'PatientController@store_prescriptions')->name('patients.prescriptions.store');
        Route::get('/patients/{patient_id}/prescriptions/{admision_id?}', 'PatientController@show_prescriptions')->name('patients.prescriptions.show');

        Route::get('/patients/{patient_id}/hc', 'PatientController@index_hc')->name('patients.hc');
        Route::get('/patients/{patient_id}/hc/create', 'PatientController@create_hc')->name('patients.hc.create');
        Route::post('/patients/{patient_id}/hc', 'PatientController@store_hc')->name('patients.hc.store');

        Route::get('/patients/{patient_id}/assignProfessional', 'PatientController@assignProfessional')->name('patients.assignProfessional');
        Route::get('/patients/{patient_id}/unAssignProfessional', 'PatientController@unAssignProfessional')->name('patients.unAssignProfessional');

        // -------------------------------

        Route::get('/professionals/report/{professional_id?}', 'ProfessionalController@report')->name('professionals.report');

        Route::resource('professionals', 'ProfessionalController');

        // -------------------------------

        Route::resource('administrators', 'AdministratorController');

        // -------------------------------

        Route::resource('accounts', 'AccountsController');

        // -------------------------------

        Route::resource('appointments', 'AppointmentController');

        Route::get('/appointments/create/{patient_id?}', 'AppointmentController@create')->name('appointment.create');

        // -------------------------------

        Route::post('/file', 'FileController@store')->name('file.store');
    }
);

Route::get(
    'link',
    function () {
        Artisan::call('storage:link');
    }
);
