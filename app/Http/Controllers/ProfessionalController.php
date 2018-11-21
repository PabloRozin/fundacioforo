<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Professional;
use App\User;
use Auth;
use Hash;
use Storage;
use Dompdf\Dompdf;

class ProfessionalController extends AdminController
{
	private $professionalData = [
		'Datos generales' => [
			'' => [
				'firstname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50|required',
				],
				'lastname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50|required',
				],
				'document_number' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Nº de documento',
					'validation' => 'integer|required',
					'not_show_to' => ['professional'],
				],
				'document_type' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Tipo de documento',
					'validation' => 'string|max:10|required',
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
					'css_class' => 'col-1-4',
					'type' => 'inputDate',
					'title' => 'Fecha de nacimiento',
					'validation' => 'date',
					'not_show_to' => ['professional'],
				],
				'profession' => [
					'css_class' => 'col-1-4',
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
					'validation' => 'string|max:20|required',
				],
				'insurance' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Seguro',
					'validation' => 'string|max:20',
				],
				'team' => [
					'css_class' => 'col-1-4',
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
					'validation' => 'string|max:10',
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
					'validation' => 'string|max:10',
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
					'validation' => 'string|max:10',
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

		if ( ! in_array(Auth::user()->permissions, ['superadmin','admin'])) {
			$data['professionals'] = $data['professionals']->where('state', 1);
		}

		$filters = false;
		
		foreach ($data['filters'] as $itemName => $filter) {
			if ( ! empty($filter['value'])) {
				if ( ! isset($filter['nested'])) {
					$data['professionals'] = $data['professionals']->{$filter['type']}($itemName, 'like', '%'.$filter['value'].'%');
				} else {
					$data['professionals'] = $data['professionals']->{$filter['type']}(function($query) use ($filter) {
						foreach($filter['nested'] as $nestedName) {
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
		if ( ! in_array(Auth::user()->permissions, ['superadmin','admin'])) {
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
		if ( ! in_array(Auth::user()->permissions, ['superadmin','admin'])) {
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
			if ( ! empty($key)) {
				$validationName = $key;
			}
			foreach ($itemGroup as $key => $itemSubroup) {
				if ( ! empty($key)) {
					$validationName = $key;
				}
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['title'])) {
						$validationName = $item['title'];
					}
					$validationNames[$itemName] = $validationName;
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$validation['password'] = $validation['password'].'|required';

		$this->validate($request, $validation, array(), $validationNames);

		$professional = new Professional;

		foreach ($this->professionalData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! isset($item['notSave']) or ! $item['notSave']) {
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
		if (in_array(Auth::user()->permissions, ['superadmin','admin'])) {
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
		if (in_array(Auth::user()->permissions, ['superadmin','admin'])) {
			$professional = $this->account->professionals()->findOrFail($id);
		} else {
			$professional = $this->account->professionals()->where('state', 1)->where('id', $id)->firstOrFail();
		}

		if ( ! in_array(Auth::user()->permissions, ['superadmin','admin']) and 
			( ! in_array(Auth::user()->permissions, ['professional']) or $professional->user_id != Auth::user()->id)
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

		if ( ! in_array(Auth::user()->permissions, ['superadmin','admin']) and 
			( ! in_array(Auth::user()->permissions, ['professional']) or $professional->user_id != Auth::user()->id)
		) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('professionals.show', ['professional_id' => $professional->id]);
		}

		$validation = [];

		foreach ($this->professionalData as $key => $itemGroup) {
			if ( ! empty($key)) {
				$validationName = $key;
			}
			foreach ($itemGroup as $key => $itemSubroup) {
				if ( ! empty($key)) {
					$validationName = $key;
				}
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['title'])) {
						$validationName = $item['title'];
					}
					$validationNames[$itemName] = $validationName;
					if ( ! in_array(Auth::user()->permissions, ['superadmin','professional']) or ! isset($item['user_data'])) {
						if ( ! empty($item['validation']) and ( ! isset($item['not_updatable']) or ! $item['not_updatable'])) {
							if ( ! isset($item['not_show_to']) or  ! in_array(Auth::user()->permissions, $item['not_show_to'])) {
								$validation[$itemName] = $item['validation'];
							}
						}
					}
				}
			}
		}

		$this->validate($request, $validation, array(), $validationNames);

		foreach ($this->professionalData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ($itemName != 'password' and ( ! isset($item['not_updatable']) or ! $item['not_updatable'])) {
						$professional->$itemName = $request->$itemName;
					}
				}
			}
		}

		$user = $this->account->users()->findOrFail($professional->user_id);

		$user->name = $request->firstname.' '.$request->lastname;

		if ( ! empty($request->password)) {
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
		if ( ! in_array(Auth::user()->permissions, ['superadmin','administrator', 'admin'])) {
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

		$this->validate($request, $validation, array(), $validationNames);

		$data = [
			'back_url' => route('professionals.index'),
			'professional_id' => ($professional_id) ? $professional_id : false,
			'since' => (strtotime($request->since) < strtotime($request->to)) ? $request->since : $request->to,
			'to' => (strtotime($request->since) < strtotime($request->to)) ? $request->to : $request->since,
			'consultationTypes' => [
				['id' => 'E.I.', 'value' => 'Entrevista Individual pacientes'],
				['id' => 'G.H.', 'value' => 'Grupo de entrenamiento en habilidades'],
				['id' => 'G.H.F.A.', 'value' => 'Grupo de entrenamiento en habilidades a familiares y allegados'],
				['id' => 'E.F.A.', 'value' => 'Entrevista familiar y allegados'],
				['id' => 'E.P.', 'value' => 'Entrevista Psiquiátrica'],
				['id' => 'I.C.', 'value' => 'Interconsulta'],
				['id' => 'otros', 'value' => 'Otros', 'with_text' => 'type_info'],
			],
		];

		$data['pdf_url'] = route('professionals.report', ['professional_id' => $professional_id]) . '?pdf=true&since='.$data['since'].'&to='.$data['to'];

		$data['professionals'] = $this->account->professionals()->where(function($query) use ($data) {
			$query->whereHas('hcDates', function ($query) use ($data) {
				$query->dateWhere('created_at', '>=', $data['since'].' 00:00:00');
				$query->dateWhere('created_at', '<=', $data['to'].' 23:59:59');
				if (in_array(Auth::user()->permissions, ['superadmin','administrator'])) {
					$query->where('type', '!=', 'otros');
				}
			})->orWhereHas('admissions', function ($query) use ($data) {
				$query->dateWhere('created_at', '>=', $data['since'].' 00:00:00');
				$query->dateWhere('created_at', '<=', $data['to'].' 23:59:59');
			});
		})->orderBy('firstname', 'ASC');

		if ($data['professional_id']) {
			$data['professionals'] = $data['professionals']->where('id', $data['professional_id']);
		}

		$data['professionals'] = $data['professionals']->get();


		if ($request->pdf) {
			$view = \View::make('pdf.professionalsReport', $data)->render();
			
			$pdf = new Dompdf();

			$pdf->loadHTML($view);
			$pdf->setPaper('A4');
			$pdf->render();
			$canvas = $pdf->get_canvas();
			$canvas->page_text(15, 15, '{PAGE_NUM} de {PAGE_COUNT}', null, 10, array(0, 0, 0));

			return $pdf->stream('reporte_professionales_'.$data['since'].'_'.$data['to'].'.pdf');
		}

		return view('professionalsReport', $data);
	}
}
