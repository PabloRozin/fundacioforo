<?php

namespace App\Http\Controllers;

use Auth;

use Storage;
use Response;
use App\Medicine;
use App\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends AdminController
{
    private $prescriptionData = [
        'Datos generales' => [
            '' => [
                'date' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputDate',
                    'title' => 'Fecha de la plantilla',
                    'validation' => 'required',
                ],
                'name' => [
                    'css_class' => 'col-1-4',
                    'type' => 'inputText',
                    'title' => 'Nombre de la plantilla',
                    'validation' => 'required|max:255',
                ],
                'prolonged_treatment' => [
                    'css_class' => 'col-1-4',
                    'type' => 'select',
                    'title' => 'Tratamiento prolongado',
                    'options' => [
                        ['id' => 1, 'value' => 'Si', 'default' => true],
                        ['id' => 0, 'value' => 'No'],
                    ],
                    'validation' => 'required',
                ],
            ],
        ],
        'Medicamentos' => [
            '' => [
                'medicines' => [
                    'css_class' => 'col-1-2',
                    'type' => 'mutiItem',
                    'title' => '',
                    'config' => [
                        'object' => 'App\\Medicine',
                        'data' => [
                            [
                                'name' => 'name',
                                'label' => 'Medicamento',
                            ],
                            [
                                'name' => 'modality',
                                'label' => 'Modalidad',
                            ],
                        ],
                        'url' => '/patients/prescriptions/medicines',
                        'help' => 'Buscá los medicamentos ya utilizados o agregá nuevos<br> <a href="http://alfabeta.net/medicamento/index-ar.jsp" target="_blank">Podés ver el vademecum haciendo click aquí</a>'
                    ],
                ],
            ],
        ],
        'Texto complementario' => [
            '' => [
                'text' => [
                    'css_class' => 'col-1-2',
                    'type' => 'textarea',
                    'title' => 'Agregá un texto complementario en la receta.',
                    'validation' => 'string',
                    'config' => [
                        'url' => '/patients/prescriptions/medicines',
                    ],
                ],
            ],
        ],
    ];

    public function medicines(Request $request) {

        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
        }

        $this->validate($request, ['qry' => 'required'], [], ['qry' => 'Búsqueda']);
        
        $medicines = $this->account
            ->medicines()
            ->limit(5)
            ->where('professional_id', Auth::user()->professional->id);

        $search_words = explode(' ', $request->qry);

        foreach ($search_words as $word) {
            $medicines = $medicines->where('name', 'LIKE', "%$word%");
        }

        $medicines = $medicines->get()->toArray();

        $medicines[] = [
            'id' => false,
            'name' => $request->qry,
            'modality' => ''
        ];

        return Response::json([
            'options' => $medicines
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function patient_index(Request $request, $patient_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
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

        if (in_array(Auth::user()->permissions, ['professional'])) {
            $professional = $this->account->professionals()->where('user_id', Auth::user()->id)->first();

            $data['prescriptions'] = $data['prescriptions']->where('professional_id', $professional->id);
        }

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
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
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
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional']) or Auth::user()->professional->profession != 'psiquiatra') {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
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
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
        }

        $patient = $this->account->patients()->findOrFail($patient_id);

        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->first();

        $professional = $this->account->professionals()->where('id', $prescription->professional_id)->first();

        $data = [
            'items' => $this->prescriptionData,
            'back_url' => route('patients.prescriptions.index', ['patient_id' => $patient->id]),
            'form_url' => route('patients.prescriptions.store', ['patient_id' => $patient_id]),
            'form_method' => 'POST',
            'title' => 'Receta del paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
            'model' => $prescription,
        ];

        foreach ($data['items'] as $key => &$itemGroup) {
            foreach ($itemGroup as $key => &$itemSubroup) {
                foreach ($itemSubroup as $itemName => &$item) {
                    $item['value'] = $prescription->$itemName;
                }
            }
        }

        return view('form', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $patient_id, $prescription_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
        }

        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->firstOrFail();

        $prescription->delete();

        $request->session()->flash('success', 'Se eliminó correctamente la plantilla.');

        return redirect()->route('patients.prescriptions.index', ['patient_id' => $patient_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function patient_store(Request $request, $patient_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional']) or Auth::user()->professional->profession != 'psiquiatra') {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
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
                    if($item['type'] == 'mutiItem') {
                        continue;
                    }

                    $prescription->$itemName = $request->$itemName;
                }
            }
        }

        $prescription->save();

        foreach ($this->prescriptionData as $key => $itemGroup) {
            foreach ($itemGroup as $key => $itemSubroup) {
                foreach ($itemSubroup as $itemName => $item) {
                    if($item['type'] == 'mutiItem') {
                        foreach($request->$itemName as $multiItemId => $multiItemData) {
                            $multiItem = $this->account
                                ->$itemName();

                            foreach ($item['config']['data'] as $key => $data) {
                                $multiItem = $multiItem->where($data['name'], $multiItemData[($data['name'])]);
                            }
                            
                            $multiItem = $multiItem->where('professional_id', Auth::user()->professional->id)
                                ->first();

                            if(! $multiItem) {
                                $multiItem = new $item['config']['object'];

                                foreach ($item['config']['data'] as $key => $data) {
                                    $multiItem->{$data['name']} = $multiItemData[($data['name'])];
                                }
                                $multiItem->professional_id = $professional->id;
                                $multiItem->account_id = $this->account->id;

                                $multiItem->save();
                            }

                            $prescription->$itemName()->attach($multiItem->id);
                        }

                        continue;
                    }
                }
            }
        }

        $request->session()->flash('success', 'Se agregó una nueva plantilla de receta.');

        return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id, 'prescription_id' => $prescription->id]);
    }

    public function patient_show(Request $request, $patient_id, $prescription_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
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
            'back_url' => route('patients.prescriptions.index', ['patient_id' => $patient->id]),
            'form_url' => route('patients.prescriptions.update', ['patient_id' => $patient->id, 'prescription_id' => $prescription->id]),
            'form_method' => 'PUT',
            'title' => 'Receta del paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
            'prescription' => $prescription,
            'patient' => $patient,
            'professional' => $professional,
            'professions' => [
                'psicologia' => 'Lic. Psicología',
                'psiquiatra' => 'Médico Psiquiatra',
                'psicopedagogia' => 'Lic. Psicopedagogía',
                'at' => 'A.T.',
                'otros' => 'Otros',
            ],
        ];

        foreach ($data['items'] as $key => &$itemGroup) {
            foreach ($itemGroup as $key => &$itemSubroup) {
                foreach ($itemSubroup as $itemName => &$item) {
                    $item['value'] = $prescription->$itemName;
                }
            }
        }

        return view('prescription', $data);
    }

    public function patient_edit(Request $request, $patient_id, $prescription_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
        }

        $patient = $this->account->patients()->findOrFail($patient_id);

        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->first();

        $professional = $this->account->professionals()->where('id', $prescription->professional_id)->first();

        $data = [
            'items' => $this->prescriptionData,
            'back_url' => route('patients.prescriptions.index', ['patient_id' => $patient->id]),
            'form_url' => route('patients.prescriptions.update', ['patient_id' => $patient->id, 'prescription_id' => $prescription->id]),
            'form_method' => 'PUT',
            'title' => 'Receta del paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
            'model' => $prescription,
        ];

        foreach ($data['items'] as $key => &$itemGroup) {
            foreach ($itemGroup as $key => &$itemSubroup) {
                foreach ($itemSubroup as $itemName => &$item) {
                    $item['value'] = $prescription->$itemName;
                }
            }
        }

        return view('form', $data);
    }

    public function patient_update(Request $request, $patient_id, $prescription_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional']) or Auth::user()->professional->profession != 'psiquiatra') {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
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
        $prescription = $this->account->prescriptions()->where('id', $prescription_id)->first();

        $this->validate($request, $validation, [], $validationNames);

        foreach ($this->prescriptionData as $key => $itemGroup) {
            foreach ($itemGroup as $key => $itemSubroup) {
                foreach ($itemSubroup as $itemName => $item) {
                    if($item['type'] == 'mutiItem') {
                        $prescription->$itemName()->detach();

                        foreach($request->$itemName as $multiItemName) {
                            $multiItem = $this->account
                                ->$itemName()
                                ->where('name', $multiItemName)
                                ->where('professional_id', Auth::user()->professional->id)
                                ->first();

                            if(! $multiItem) {
                                $multiItem = new $item['config']['object'];

                                $multiItem->name = $multiItemName;
                                $multiItem->professional_id = $professional->id;
                                $multiItem->account_id = $this->account->id;

                                $multiItem->save();
                            }

                            $prescription->$itemName()->attach($multiItem->id);
                        }

                        continue;
                    }

                    $prescription->$itemName = $request->$itemName;
                }
            }
        }

        $prescription->save();

        $request->session()->flash('success', 'Se editó con éxito la plantilla de receta.');

        return redirect()->route('patients.prescriptions.show', ['patient_id' => $patient_id, 'prescription_id' => $prescription->id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function professional_index(Request $request, $professional_id)
    {
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('professionals.prescriptions.edit', ['professional_id' => $professional_id]);
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
        if (! in_array(Auth::user()->id, [145, 80]) and ! in_array(Auth::user()->email, ['pablorozin91@gmail.com', 'demianrodante@hotmail.com'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.index');
        }

        if (! in_array(Auth::user()->permissions, ['professional', 'admin'])) {
            $request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

            return redirect()->route('patients.prescriptions.edit', ['patient_id' => $patient_id]);
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
