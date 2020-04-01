<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Professional;
use App\Patient;
use App\User;
use Auth;
use Hash;
use Storage;
use Dompdf\Dompdf;

class AppointmentController extends AdminController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if ( ! in_array(Auth::user()->permissions, ['professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$data['appointments'] = Auth::user()->professional->appointments();

		return view('appointments', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $patient_id = false)
	{
		if ( ! in_array(Auth::user()->permissions, ['professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$patients_data = [];

		$professional_patients = $Auth::user()->professional->patients();

		if ($patient_id) {
			$professional_patients = $professional_patients->where('patient_id', $patient_id);
		}

		$professional_patients = $professional_patients->get();

		foreach ($professional_patients as $key => $patient) {
			
			$patients_data[] = [
				'id' => $patient->id,
				'value' => $patient->patient_firstname . ' ' . $patient->patient_lastname
			];
		}

		$appointmentData = [
			'patient_id' => [
				'css_class' => 'col-1-6',
				'type' => 'select',
				'title' => 'Habilitado',
				'options' => $patients_data,
				'validation' => 'boolean',
			],
			'date' => [
				'css_class' => 'col-1-4',
				'type' => 'inputDate',
				'title' => 'Fecha',
				'validation' => 'required|date',
			],
		];

		$data = [
			'items' => $this->professionalData,
			'back_url' => route('appointments.index'),
			'form_url' => route('appointments.store'),
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
		if ( ! in_array(Auth::user()->permissions, ['professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		return redirect()->route('appointments.index');
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

		if ( ! in_array(Auth::user()->permissions, ['admin']) and 
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

		if ( ! in_array(Auth::user()->permissions, ['admin']) and 
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
					if ( ! in_array(Auth::user()->permissions, ['professional']) or ! isset($item['user_data'])) {
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
		if ( ! in_array(Auth::user()->permissions, ['administrator', 'admin'])) {
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
				if (in_array(Auth::user()->permissions, ['administrator'])) {
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
