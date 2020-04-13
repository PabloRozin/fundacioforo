<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Storage;
use App\Prescription;

class PrescriptionController extends AdminController
{
    private $prescriptionData = [
        'Datos generales' => [
            '' => [
                'date' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputDate',
                    'title' => 'Fecha de la receta',
                    'validation' => 'required',
                ],
                'items_per_prescription' => [
                    'css_class' => 'col-1-4',
                    'type' => 'select',
                    'title' => 'Cantidad de medicamentos por receta',
                    'options' => [
                        ['id' => 1, 'value' => '1'],
                        ['id' => 2, 'value' => '2'],
                        ['id' => 3, 'value' => '3'],
                        ['id' => 4, 'value' => '4'],
                        ['id' => 5, 'value' => '5', 'defalut' => true],
                    ],
                    'validation' => 'required|integer|min:1|max:5',
                ],
            ],
        ],
        'Medicamentos' => [
            '' => [
                'medicine-1' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'required|string|max:255',
                ],
                'medicine-2' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-3' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-4' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-5' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-6' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-7' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-8' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-9' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-10' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-11' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-12' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-13' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-14' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-15' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-16' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-17' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-18' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-19' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
                'medicine-20' => [
                    'css_class' => 'col-1-4',
                    'type' => 'textarea',
                    'title' => 'Medicamento',
                    'validation' => 'string|max:255',
                ],
            ],
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function patient_index(Request $request, $patient_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
        }

        $data['filters'] = [
            'date' => [
                'type' => 'where',
                'value' => $request->input('date'),
            ],
            'professional_firstname' => [
                'type' => 'where',
                'value' => $request->input('professional_firstname'),
            ],
            'professional_lastname' => [
                'type' => 'where',
                'value' => $request->input('professional_firstname'),
            ],
        ];

        $data['patient'] = $this->account->patients()->findOrFail($patient_id);

        $data['prescriptions'] = $data['patient']->prescriptions()
            ->select('prescriptions.*', 'professionals.firstname as professional_firstname', 'professionals.lastname as professional_lastname')
            ->leftJoin('professionals', 'professionals.id', '=', 'prescriptions.professional_id')
            ->orderBy('date', 'DESC');

        $filters = false;

        foreach ($data['filters'] as $itemName => $filter) {
            if (! empty($filter['value'])) {
                $data['prescriptions'] = $data['prescriptions']->{$filter['type']}($itemName, 'like', '%'.str_replace(' ', '%', $filter['value']).'%');
                $filters = true;
            }
        }

        $data['back_url'] = route('patients.index');

        if ($filters) {
            $data['back_url'] = route('patients.prescriptions.index', ['patient_id' => $data['patient']->id]);
        }

        $data['prescriptions'] = $data['prescriptions']->paginate(20);

        return view('patientPrescriptions', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function patient_report(Request $request, $patient_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
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

        $data['since'] = $since = (strtotime($request->since) < strtotime($request->to)) ? $request->since : $request->to;
        $data['to'] = $to = (strtotime($request->since) < strtotime($request->to)) ? $request->to : $request->since;

        $data['patient'] = $this->account->patients()->findOrFail($patient_id);

        $prescriptions = $data['patient']->prescriptions()
            ->select('prescriptions.*', 'professionals.firstname as professional_firstname')
            ->leftJoin('professionals', 'professionals.id', '=', 'prescriptions.professional_id')
            ->orderBy('professionals.firstname', 'ASC')
            ->orderBy('date', 'DESC')
            ->dateWhere('prescriptions.date', '>=', $since.' 00:00:00')
            ->dateWhere('prescriptions.date', '<=', $to.' 23:59:59')
            ->get();

        foreach ($prescriptions as $key => $prescription) {
            $data['professionals'][$prescription->professional_id]['data'] = $prescription->professional;
            $data['professionals'][$prescription->professional_id]['prescriptions'][] = $prescription;
        }

        $data['back_url'] = route('patients.prescriptions.index', ['patient_id' => $data['patient']->id]);

        return view('patientPrescriptionsReport', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function patient_create(Request $request, $patient_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional']) or Auth::user()->professional->profession != 'psiquiatra') {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
        }

        $this->prescriptionData['Datos generales']['']['date']['min'] = date('Y-m-d');
        $this->prescriptionData['Datos generales']['']['date']['max'] = date('Y-m-d', time() + 180*24*60*60);
        $this->prescriptionData['Datos generales']['']['date']['value'] = date('Y-m-d');

        $patient = $this->account->patients()->findOrFail($patient_id);

        $data = [
            'items' => $this->prescriptionData,
            'back_url' => route('patients.prescriptions.index', ['patient_id' => $patient_id]),
            'form_url' => route('patients.prescriptions.store', ['patient_id' => $patient_id]),
            'form_method' => 'POST',
            'title' => 'Crear nueva receta para el paciente ' . $patient->patient_firstname . ' ' . $patient->patient_lastname,
        ];

        return view('form', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function patient_duplicate(Request $request, $patient_id, $prescription_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional']) or Auth::user()->professional->profession != 'psiquiatra') {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
        }

        $patient = $this->account->patients()->findOrFail($patient_id);

        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->where('patient_id', $patient_id)->first();

        $data = [
            'items' => $this->prescriptionData,
            'back_url' => route('patients.prescriptions.index', ['patient_id' => $patient_id]),
            'form_url' => route('patients.prescriptions.store', ['patient_id' => $patient_id]),
            'form_method' => 'POST',
            'title' => 'Crear nueva receta para el paciente ' . $patient->patient_firstname . ' ' . $patient->patient_lastname,
        ];

        foreach ($data['items'] as $key => &$itemGroup) {
            foreach ($itemGroup as $key => &$itemSubroup) {
                foreach ($itemSubroup as $itemName => &$item) {
                    $item['value'] = $prescription->$itemName;
                }
            }
        }

        $data['items']['Datos generales']['']['date']['min'] = date('Y-m-d');
        $data['items']['Datos generales']['']['date']['max'] = date('Y-m-d', time() + 180*24*60*60);
        $data['items']['Datos generales']['']['date']['value'] = date('Y-m-d');

        return view('form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function patient_store(Request $request, $patient_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional']) or Auth::user()->professional->profession != 'psiquiatra') {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
        }
        $validation = [];

        foreach ($this->prescriptionData as $key => $itemGroup) {
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
                    if (! empty($item['validation'])) {
                        $validation[$itemName] = $item['validation'];
                        $validationNames[$itemName] = $validationName;
                    }
                }
            }
        }

        $patient = $this->account->patients()->findOrFail($patient_id);
        $professional = $this->account->professionals()->where('user_id', Auth::user()->id)->first();

        $prescription = new Prescription;
        $prescription->professional_id = $professional->id;
        $prescription->account_id = $this->account->id;
        $prescription->patient_id = $patient_id;

        $this->validate($request, $validation, [], $validationNames);

        foreach ($this->prescriptionData as $key => $itemGroup) {
            foreach ($itemGroup as $key => $itemSubroup) {
                foreach ($itemSubroup as $itemName => $item) {
                    $prescription->$itemName = $request->$itemName;
                }
            }
        }

        $prescription->save();

        $request->session()->flash('success', 'Se editaron con éxito los datos de admisión del paciente.');

        return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id, 'prescription_id' => $prescription->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patient_show(Request $request, $patient_id, $prescription_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
        }

        $patient = $this->account->patients()->findOrFail($patient_id);

        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->first();

        $professional = $this->account->professionals()->where('id', $prescription->professional_id)->first();

        $data = [
            'items' => $this->prescriptionData,
            'form_method' => 'PUT',
            'title' => 'Receta del paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
            'only_view' => true,
            'patient' => $patient,
            'professional' => $professional,
            'prescription' => $prescription,
        ];

        $data['professions'] = [
            'psicologia' => 'Lic. Psicología',
            'psiquiatra' => 'Médico Psiquiatra',
            'psicopedagogia' => 'Lic. Psicopedagogía',
            'at' => 'A.T.',
            'otros' => 'Otro',
        ];

        $data['back_url'] = route('patients.prescriptions.index', ['patient_id' => $patient->id]);

        return view('prescription', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function professional_index(Request $request, $professional_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('professionals.prescriptions.show', ['professional_id' => $professional_id]);
        }

        $data['filters'] = [
            'date' => [
                'type' => 'where',
                'value' => $request->input('date'),
            ],
            'patient_firstname' => [
                'type' => 'where',
                'value' => $request->input('patient_firstname'),
            ],
            'patient_lastname' => [
                'type' => 'where',
                'value' => $request->input('patient_lastname'),
            ],
        ];

        $data['professional'] = $this->account->professionals()->findOrFail($professional_id);

        $data['prescriptions'] = $data['professional']->prescriptions()
            ->select('prescriptions.*', 'patients.patient_firstname as patient_firstname', 'patients.patient_lastname as patient_lastname')
            ->leftJoin('patients', 'patients.id', '=', 'prescriptions.patient_id')
            ->orderBy('date', 'DESC');

        $filters = false;

        foreach ($data['filters'] as $itemName => $filter) {
            if (! empty($filter['value'])) {
                $data['prescriptions'] = $data['prescriptions']->{$filter['type']}($itemName, 'like', '%'.str_replace(' ', '%', $filter['value']).'%');
                $filters = true;
            }
        }

        $data['prescriptions'] = $data['prescriptions']->paginate(20);

        $data['back_url'] = route('professionals.index');

        if ($filters) {
            $data['back_url'] = route('professionals.prescriptions.index', ['patient_id' => $data['professional']->id]);
        }

        return view('professionalPrescriptions', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function professional_show(Request $request, $professional_id, $prescription_id)
    {
        if (Auth::user()->id != 145 and Auth::user()->id != 80) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id]);
        }

        $professional = $this->account->professionals()->findOrFail($professional_id);

        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->first();

        $patient = $this->account->patients()->where('id', $prescription->patient_id)->first();

        $data = [
            'items' => $this->prescriptionData,
            'form_method' => 'PUT',
            'title' => 'Receta del paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
            'only_view' => true,
            'patient' => $patient,
            'professional' => $professional,
            'prescription' => $prescription,
        ];

        $data['professions'] = [
            'psicologia' => 'Lic. Psicología',
            'psiquiatra' => 'Médico Psiquiatra',
            'psicopedagogia' => 'Lic. Psicopedagogía',
            'at' => 'A.T.',
            'otros' => 'Otro',
        ];

        $data['back_url'] = route('professionals.prescriptions.index', ['professional_id' => $professional->id]);

        return view('prescription', $data);
    }
}
