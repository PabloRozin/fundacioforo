<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;
use Hash;

class AdministratorController extends AdminController
{
	private $administratorData = [
		'Datos de usuario' => [
			'' => [
				'name' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre y Apellido',
					'validation' => 'string|max:50',
					'notSave' => true,
				],
				'email' => [
					'css_class' => 'col-1-4',
					'type' => 'inputEmail',
					'title' => 'Email',
					'validation' => 'string|max:250|required|unique:users',
					'notSave' => true,
				],
				'password' => [
					'css_class' => 'col-1-4',
					'type' => 'inputPassword',
					'title' => 'Contraseña',
					'validation' => 'string|min:5|max:50',
					'notSave' => true,
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
		if ( ! in_array(Auth::user()->permissions, ['admin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$data['administrators'] = $this->account->users()->orderBy('name', 'ASC')->where('permissions', 'administrator')->paginate(20);

		return view('administrators', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		if ( ! in_array(Auth::user()->permissions, ['admin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$administrator_quantity = $this->account->administrator()->count();

		if ($this->account->administrator_limit > 0 and $administrator_quantity >= $this->account->administrator_limit) {
			$request->session()->flash('error', 'Llegaste a tu límite de usuarios administrativos, contactate para aumentarlo.');

			return redirect()->route('dashboard');
		}

		$data = [
			'items' => $this->administratorData,
			'back_url' => route('administrators.index'),
			'form_url' => route('administrators.store'),
			'form_method' => 'POST',
			'title' => 'Crear nuevo Administrador',
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
		if ( ! in_array(Auth::user()->permissions, ['admin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}
		
		$validation = [];

		foreach ($this->administratorData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$validation['password'] = $validation['password'].'|required';

		$this->validate($request, $validation);

		$user = new User;

		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->permissions = 'administrator';
		$user->account_id = $this->account->id;

		$user->save();

		$request->session()->flash('success', 'El usuario administrativo fue creado con éxito.');

		return redirect()->route('administrators.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		if ( ! in_array(Auth::user()->permissions, ['admin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}
		
		$administrator = $this->account->users()->where('id', $id)->where('permissions', 'administrator')->first();

		$data = [
			'items' => $this->administratorData,
			'back_url' => route('administrators.index'),
			'form_url' => route('administrators.update', ['id' => $id]),
			'form_method' => 'PUT',
			'title' => 'Administrador "' . $administrator['name'] . '"',
		];

		foreach ($data['items'] as $key => &$itemGroup) {
			foreach ($itemGroup as $key => &$itemSubroup) {
				foreach ($itemSubroup as $itemName => &$item) {
					$item['value'] = $administrator->$itemName;
				}
			}
		}

		$item['firstname'] = explode(' ', $administrator->name)[0];
		if (isset(explode(' ', $administrator->name)[1])) {
			$item['lastname'] = explode(' ', $administrator->name)[1];
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
		if ( ! in_array(Auth::user()->permissions, ['admin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}
		
		$administrator = $this->account->users()->where('id', $id)->where('permissions', 'administrator')->first();

		$validation = [];

		foreach ($this->administratorData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$this->validate($request, $validation);

		$user = $this->account->users()->findOrFail($professional->user_id);

		$user->name = $request->name;
		if ( ! empty($request->password)) {
			$user->password = Hash::make($request->password);
		}

		$user->save();

		$professional->save();

		$request->session()->flash('success', 'Se editaron con éxito los datos.');

		return redirect()->route('administrators.index');
	}
}
