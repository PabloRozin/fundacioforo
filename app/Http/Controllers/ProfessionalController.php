<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Professional;
use App\User;
use Auth;
use Hash;
use Storage;

class ProfessionalController extends AdminController
{
    private $professionalData = [
        'Datos generales' => [
            '' => [
                'firstname' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Nombre',
                    'validation' => 'required|string|max:50',
                ],
                'lastname' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Apellido',
                    'validation' => 'required|string|max:50',
                ],
                'document_number' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Nº de documento',
                    'validation' => 'required|alpha_num',
                    'not_show_to' => ['professional'],
                ],
                'document_type' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Tipo de documento',
                    'validation' => 'required|string|max:10',
                    'not_show_to' => ['professional'],
                ],
                'cuit' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'CUIT',
                    'validation' => 'string|max:20',
                    'not_show_to' => ['professional'],
                ],
                'birthdate' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputDate',
                    'title' => 'Fecha de nacimiento',
                    'validation' => 'date',
                    'not_show_to' => ['professional'],
                ],
                'profession' => [
                    'css_class' => 'col-1-6',
                    'type' => 'select',
                    'title' => 'Profesión',
                    'options' => [
                        ['id' => 'psicologia', 'value' => 'Lic. Psicología'],
                        ['id' => 'psiquiatra', 'value' => 'Médico Psiquiatra'],
                        ['id' => 'psicopedagogia', 'value' => 'Lic. Psicopedagogía'],
                        ['id' => 'at', 'value' => 'A.T.'],
                        ['id' => 'otros', 'value' => 'Otros'],
                    ],
                    'validation' => 'in:psicologia,psiquiatra,psicopedagogia,at,otros',
                ],
                'registration_number' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Matrícula',
                    'validation' => 'required|string|max:20',
                ],
                'insurance' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Seguro',
                    'validation' => 'string|max:20',
                ],
                'team' => [
                    'css_class' => 'col-1-6',
                    'type' => 'select',
                    'title' => 'Equipo',
                    'options' => [
                        ['id' => 'dbt', 'value' => 'DBT'],
                        ['id' => 'clinica_general', 'value' => 'Clínica general'],
                        ['id' => 'otros', 'value' => 'Otros'],
                    ],
                    'validation' => 'in:dbt,clinica_general,,otros',
                ],
                'admision_date' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputDate',
                    'title' => 'Fecha de ingreso',
                    'validation' => 'date',
                    'not_show_to' => ['professional'],
                ],
                'prescription_cv' => [
                    'css_class' => 'col-1-2',
                    'type' => 'textarea',
                    'title' => 'CV en recetas',
                    'validation' => '',
                ],
                'prescription_signature' => [
                    'css_class' => 'col-1-2',
                    'type' => 'textarea',
                    'title' => 'Firma en recetas',
                    'validation' => '',
                ],
            ],
        ],
        'Datos de Usuario' => [
            '' => [
                'email' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputEmail',
                    'title' => 'Email',
                    'validation' => 'string|max:250|required|unique:users',
                    'user_data' => true,
                    'not_updatable' => true,
                ],
                'password' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputPassword',
                    'title' => 'Contraseña',
                    'validation' => 'string|min:5|max:50',
                    'notSave' => true,
                    'user_data' => true,
                ],
                'state' => [
                    'css_class' => 'col-1-6',
                    'type' => 'select',
                    'title' => 'Habilitado',
                    'options' => [
                        ['id' => 0, 'value' => 'No'],
                        ['id' => 1, 'value' => 'Si', 'defalut' => true],
                    ],
                    'validation' => 'boolean',
                    'user_data' => true,
                ],
            ],
        ],
        'Teléfonos' => [
            'Teléfono 1' => [
                'phone_1' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Número',
                    'validation' => 'string|max:20',
                ],
                'phone_info_1' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Información del teléfono',
                    'validation' => 'string|max:250',
                ],
            ],
            'Teléfono 2' => [
                'phone_2' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Número',
                    'validation' => 'string|max:20',
                ],
                'phone_info_2' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Información del teléfono',
                    'validation' => 'string|max:250',
                ],
            ],
            'Teléfono 3' => [
                'phone_3' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Número',
                    'validation' => 'string|max:20',
                ],
                'phone_info_3' => [
                    'css_class' => 'col-1-6',
                    'type' => 'inputText',
                    'title' => 'Información del teléfono',
                    'validation' => 'string|max:250',
                ],
            ],
        ],
        'Direcciones' => [
            'Dirección 1' => [
                'street_1' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Calle',
                    'validation' => 'string|max:50',
                ],
                'number_1' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'Número',
                    'validation' => 'string',
                ],
                'flat_1' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'Dpto.',
                    'validation' => 'string|max:50',
                ],
                'city_1' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Ciudad',
                    'validation' => 'string|max:50',
                ],
                'district_1' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Barrio',
                    'validation' => 'string|max:50',
                ],
                'postal_code_1' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'C. postal',
                    'validation' => 'string|max:10',
                ],
                'address_info_1' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Información',
                    'validation' => 'string|max:20',
                ],
            ],
            'Dirección 2' => [
                'street_2' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Calle',
                    'validation' => 'string|max:50',
                ],
                'number_2' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'Número',
                    'validation' => 'string',
                ],
                'flat_2' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'Dpto.',
                    'validation' => 'string|max:50',
                ],
                'city_2' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Ciudad',
                    'validation' => 'string|max:50',
                ],
                'district_2' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Barrio',
                    'validation' => 'string|max:50',
                ],
                'postal_code_2' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'C. postal',
                    'validation' => 'string|max:10',
                ],
                'address_info_2' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Información',
                    'validation' => 'string|max:20',
                ],
            ],
            'Dirección 3' => [
                'street_3' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Calle',
                    'validation' => 'string|max:50',
                ],
                'number_3' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'Número',
                    'validation' => 'string',
                ],
                'flat_3' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'Dpto.',
                    'validation' => 'string|max:50',
                ],
                'city_3' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Ciudad',
                    'validation' => 'string|max:50',
                ],
                'district_3' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Barrio',
                    'validation' => 'string|max:50',
                ],
                'postal_code_3' => [
                    'css_class' => 'col-1-12',
                    'type' => 'inputText',
                    'title' => 'C. postal',
                    'validation' => 'string|max:10',
                ],
                'address_info_3' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Información',
                    'validation' => 'string|max:20',
                ],
            ],
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['filters'] = [
            'id' => [
                'type' => 'where',
                'value' => $request->input('id'),
            ],
            'document_number' => [
                'type' => 'where',
                'value' => $request->input('document_number'),
            ],
            'registration_number' => [
                'type' => 'where',
                'value' => $request->input('registration_number'),
            ],
            'firstname' => [
                'type' => 'where',
                'value' => $request->input('firstname'),
            ],
            'lastname' => [
                'type' => 'where',
                'value' => $request->input('lastname'),
            ],
            'profession' => [
                'type' => 'where',
                'value' => $request->input('profession'),
            ],
            'email' => [
                'type' => 'where',
                'value' => $request->input('email'),
            ],
            'district_1' => [
                'type' => 'where',
                'value' => $request->input('district'),
                'nested' => ['district_1','district_2', 'district_3'],
                'nested_type' => 'orWhere'
            ],
        ];

        $data['professionals'] = $this->account->professionals()->orderBy('firstname', 'ASC');

        if (! in_array(Auth::user()->permissions, ['admin'])) {
            $data['professionals'] = $data['professionals']->where('state', 1);
        }

        $filters = false;

        foreach ($data['filters'] as $itemName => $filter) {
            if (! empty($filter['value'])) {
                if (! isset($filter['nested'])) {
                    $data['professionals'] = $data['professionals']->{$filter['type']}($itemName, 'like', '%'.$filter['value'].'%');
                } else {
                    $data['professionals'] = $data['professionals']->{$filter['type']}(function ($query) use ($filter) {
                        foreach ($filter['nested'] as $nestedName) {
                            $query->{$filter['nested_type']}($nestedName, 'like', '%'.$filter['value'].'%');
                        }
                    });
                }
                $filters = true;
            }
        }

        if ($filters) {
            $data['back_url'] = route('professionals.index');
        }

        $data['professionals'] = $data['professionals']->paginate(20);

        return view('professionals', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (! in_array(Auth::user()->permissions, ['admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('dashboard');
        }

        $professionals_quantity = $this->account->professionals()->count();

        if ($this->account->professionals_limit == 0 or $professionals_quantity > $this->account->professionals_limit) {
            $request->session()->flash('error', 'Llegaste a tu límite de profesionales, contactate para aumentarlo.');

            return redirect()->route('dashboard');
        }

        $data = [
            'items' => $this->professionalData,
            'back_url' => route('professionals.index'),
            'form_url' => route('professionals.store'),
            'form_method' => 'POST',
            'title' => 'Crear nuevo profesional',
        ];

        return view('form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! in_array(Auth::user()->permissions, ['admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('dashboard');
        }

        $professionals_quantity = $this->account->professionals()->count();

        if ($this->account->professionals_limit == 0 or $professionals_quantity > $this->account->professionals_limit) {
            $request->session()->flash('error', 'Llegaste a tu límite de profesionales, contactate para aumentarlo.');

            return redirect()->route('dashboard');
        }

        $validation = [];

        foreach ($this->professionalData as $key => $itemGroup) {
            if (! empty($key)) {
                $validationName = $key;
            }
            foreach ($itemGroup as $key => $itemSubroup) {
                if (! empty($key)) {
                    $validationName = $key;
                }
                foreach ($itemSubroup as $itemName => $item) {
                    if (! empty($item['title'])) {
                        $validationName = $item['title'];
                    }
                    $validationNames[$itemName] = $validationName;
                    if (! empty($item['validation'])) {
                        $validation[$itemName] = $item['validation'];
                    }
                }
            }
        }

        $validation['password'] = $validation['password'].'|required';

        $this->validate($request, $validation, [], $validationNames);

        $professional = new Professional;

        foreach ($this->professionalData as $key => $itemGroup) {
            foreach ($itemGroup as $key => $itemSubroup) {
                foreach ($itemSubroup as $itemName => $item) {
                    if (! isset($item['notSave']) or ! $item['notSave']) {
                        $professional->$itemName = $request->$itemName;
                    }
                }
            }
        }

        $user = new User;

        $user->name = $request->firstname.' '.$request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->permissions = 'professional';
        $user->account_id = $this->account->id;

        $user->save();

        $professional->user_id = $user->id;
        $professional->account_id = $this->account->id;

        $professional->save();

        foreach ($this->account->patients as $key => $patient) {
            if (! $patient->professional_state) {
                $professional->asignedPatients()->attach($patient->id);
            }
        }

        $professional->save();

        $request->session()->flash('success', 'El profesional fue creado con éxito.');

        return redirect()->route('professionals.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (in_array(Auth::user()->permissions, ['admin'])) {
            $professional = $this->account->professionals()->findOrFail($id);
        } else {
            $professional = $this->account->professionals()->where('state', 1)->where('id', $id)->firstOrFail();
        }

        $data = [
            'items' => $this->professionalData,
            'back_url' => route('professionals.index'),
            'form_url' => route('professionals.update', ['id' => $id]),
            'form_method' => 'PUT',
            'title' => 'Profesional "' . $professional['firstname'] . ' ' . $professional['lastname'] . '"',
            'only_view' => true,
        ];

        foreach ($data['items'] as $key => &$itemGroup) {
            foreach ($itemGroup as $key => &$itemSubroup) {
                foreach ($itemSubroup as $itemName => &$item) {
                    $item['value'] = $professional->$itemName;
                }
            }
        }

        return view('form', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array(Auth::user()->permissions, ['admin'])) {
            $professional = $this->account->professionals()->findOrFail($id);
        } else {
            $professional = $this->account->professionals()->where('state', 1)->where('id', $id)->firstOrFail();
        }

        if (! in_array(Auth::user()->permissions, ['admin']) and
            (! in_array(Auth::user()->permissions, ['professional']) or $professional->user_id != Auth::user()->id)
        ) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('professionals.show', ['professional_id' => $professional->id]);
        }

        $data = [
            'items' => $this->professionalData,
            'back_url' => route('professionals.index'),
            'form_url' => route('professionals.update', ['id' => $id]),
            'form_method' => 'PUT',
            'title' => 'Profesional "' . $professional['firstname'] . ' ' . $professional['lastname'] . '"',
            'edit' => true
        ];

        foreach ($data['items'] as $key => &$itemGroup) {
            foreach ($itemGroup as $key => &$itemSubroup) {
                foreach ($itemSubroup as $itemName => &$item) {
                    $item['value'] = $professional->$itemName;
                }
            }
        }

        return view('form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $professional = $this->account->professionals()->findOrFail($id);

        if (! in_array(Auth::user()->permissions, ['admin']) and
            (! in_array(Auth::user()->permissions, ['professional']) or $professional->user_id != Auth::user()->id)
        ) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('professionals.show', ['professional_id' => $professional->id]);
        }

        $validation = [];

        foreach ($this->professionalData as $key => $itemGroup) {
            if (! empty($key)) {
                $validationName = $key;
            }
            foreach ($itemGroup as $key => $itemSubroup) {
                if (! empty($key)) {
                    $validationName = $key;
                }
                foreach ($itemSubroup as $itemName => $item) {
                    if (! empty($item['title'])) {
                        $validationName = $item['title'];
                    }
                    $validationNames[$itemName] = $validationName;
                    if (in_array(Auth::user()->permissions, ['admin']) or ! isset($item['user_data'])) {
                        if (! empty($item['validation']) and (! isset($item['not_updatable']) or ! $item['not_updatable'])) {
                            if (! isset($item['not_show_to']) or  ! in_array(Auth::user()->permissions, $item['not_show_to'])) {
                                $validation[$itemName] = $item['validation'];
                            }
                        }
                    }
                }
            }
        }

        $this->validate($request, $validation, [], $validationNames);

        foreach ($this->professionalData as $key => $itemGroup) {
            foreach ($itemGroup as $key => $itemSubroup) {
                foreach ($itemSubroup as $itemName => $item) {
                    if ($itemName != 'password' and (! isset($item['not_updatable']) or ! $item['not_updatable'])) {
                        if ($request->$itemName) {
                            $professional->$itemName = $request->$itemName;
                        }
                    }
                }
            }
        }

        $user = $this->account->users()->findOrFail($professional->user_id);

        $user->name = $request->firstname.' '.$request->lastname;

        if (! empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $professional->save();

        $request->session()->flash('success', 'Se editaron con éxito los datos.');

        return redirect()->route('professionals.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request, $professional_id = false)
    {
        if (! in_array(Auth::user()->permissions, ['administrator', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('professionals.index');
        }

        $validation = [
            'since' => 'required|date',
            'to' => 'required|date',
        ];

        $validationNames = [
            'since' => 'Desde',
            'to' => 'Hasta',
        ];

        $this->validate($request, $validation, [], $validationNames);

        $consultationTypes = [
            'E.I.' => 'Entrevista Individual pacientes',
            'G.H.' => 'Grupo de entrenamiento en habilidades',
            'G.H.F.A.' => 'Grupo de entrenamiento en habilidades a familiares y allegados',
            'E.F.A.' => 'Entrevista familiar y allegados',
            'E.P.' => 'Entrevista Psiquiátrica',
            'I.C.' => 'Interconsulta',
            'T.P.' => 'Terapia de Pareja',
            'T.F.S.' => 'Taller de Fobia Social',
            'T.M.' => 'Taller Multifamiliar',
            'O.F.A.' => 'Orientación a Familiares y/o Allegados',
            'T.E.P.T.' => 'Terapia de Exposición Post Traumática',
            'otros' => 'Otros',
        ];

        $since = (strtotime($request->since) < strtotime($request->to)) ? $request->since : $request->to;
        $to = (strtotime($request->since) < strtotime($request->to)) ? $request->to : $request->since;

        $data = [
            'back_url' => route('professionals.index'),
            'professional_id' => ($professional_id) ? $professional_id : false,
            'since' => $since,
            'to' => $to,
            'consultationTypes' => $consultationTypes,
            'pdf' => $request->pdf
        ];

        $hcDates = $this->account->hcDates()
            ->select('hc_dates.*', 'professionals.firstname as firstname')
            ->leftJoin('professionals', 'professionals.id', '=', 'hc_dates.professional_id')
            ->where('hc_dates.created_at', '>=', $since.' 00:00:00')
            ->where('hc_dates.created_at', '<=', $to.' 23:59:59')
            ->orderBy('professionals.firstname', 'ASC')
            ->orderBy('hc_dates.type', 'ASC')
            ->orderBy('hc_dates.professional_id', 'ASC')
            ->orderBy('hc_dates.created_at', 'ASC');

        if ($data['professional_id']) {
            $hcDates = $hcDates->where('professional_id', $data['professional_id']);
        }

        $data['hcDates']['professionals'] = [];
        
        $hcDates = $hcDates->get();

        foreach ($hcDates as $key => $hcDate) {
            $data['hcDates']['professionals'][$hcDate->professional_id]['data'] = $hcDate->professional;
            $data['hcDates']['professionals'][$hcDate->professional_id]['consultationTypes'][$hcDate->type]['patients'][$hcDate->patient_id]['dates'][] = $hcDate;
            $data['hcDates']['professionals'][$hcDate->professional_id]['consultationTypes'][$hcDate->type]['patients'][$hcDate->patient_id]['data'] = $hcDate->patient;
            $data['hcDates']['professionals'][$hcDate->professional_id]['consultationTypes'][$hcDate->type]['count'] = (! isset($data['hcDates']['professionals'][$hcDate->professional_id]['consultationTypes'][$hcDate->type]['count'])) ? 1 : $data['hcDates']['professionals'][$hcDate->professional_id]['consultationTypes'][$hcDate->type]['count'] + 1;
        }

        $admissions = $this->account->patientAdmissions()
            ->select('patient_admissions.*', 'professionals.firstname as firstname')
            ->leftJoin('professionals', 'professionals.id', '=', 'patient_admissions.professional_id')
            ->orderBy('professionals.firstname', 'ASC')
            ->orderBy('patient_admissions.professional_id', 'ASC')
            ->orderBy('patient_admissions.created_at', 'ASC')
            ->dateWhere('patient_admissions.created_at', '>=', $since.' 00:00:00')
            ->dateWhere('patient_admissions.created_at', '<=', $to.' 23:59:59');

        if ($data['professional_id']) {
            $admissions = $admissions->where('professional_id', $data['professional_id']);
        }

        $admissions = $admissions->get();

        foreach ($admissions as $key => $admission) {
            if (! isset($data['hcDates']['professionals'][$admission->professional_id])) {
                $data['hcDates']['professionals'][$admission->professional_id]['data'] = $this->account->professionals()->find($admission->professional_id);
                $data['hcDates']['professionals'][$admission->professional_id]['consultationTypes'] = [];
            }
            $data['hcDates']['professionals'][$admission->professional_id]['admissions'][] = $admission;
        }

        return view('professionalsReport', $data);
    }
}
