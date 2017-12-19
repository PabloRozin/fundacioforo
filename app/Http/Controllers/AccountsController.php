<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Account;
use Auth;
use Hash;

class AccountsController extends AdminController
{
	private $accountsData = [
		'Datos de la cuenta' => [
			'' => [
				'accountName' => [
					'css_class' => 'col',
					'type' => 'inputText',
					'title' => 'Nombre de la cuenta',
					'validation' => 'string|max:255|unique:accounts',
					'notSave' => true,
					'not_updatable' => true,
				],
				'professionals_limit' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Límite de profesionales (0 es ilimitados)',
					'validation' => 'integer|min:0|required',
					'notSave' => true,
				],
				'patients_limit' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Límite de pacientes (0 es ilimitados)',
					'validation' => 'integer|min:0|required',
					'notSave' => true,
				],
				'administrator_limit' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Límite de usuarios administrativos (0 es ilimitados)',
					'validation' => 'integer|min:0|required',
					'notSave' => true,
				],
			],
		],
		'Datos del usuario Admin' => [
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
					'not_updatable' => true,
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
		if ( ! in_array(Auth::user()->permissions, ['superadmin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$data['accounts'] = $this->account->orderBy('id', 'ASC')->paginate(20);

		return view('accounts', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		if ( ! in_array(Auth::user()->permissions, ['superadmin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$data = [
			'items' => $this->accountData,
			'back_url' => route('accounts.index'),
			'form_url' => route('accounts.store'),
			'form_method' => 'POST',
			'title' => 'Crear nueva Cuenta',
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
		if ( ! in_array(Auth::user()->permissions, ['superadmin'])) {
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

		$account = new User;

		$account->account = $request->account;
		$account->professionals_limit = $request->professionals_limit;
		$account->patients_limit = $request->patients_limit;
		$account->administrator_limit = $request->administrator_limit;

		$account->save();

		$user = new User;

		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->permissions = 'admin';
		$user->account_id = $account->id;

		$user->save();

		$request->session()->flash('success', 'La cuenta fue creada con éxito.');

		return redirect()->route('accounts.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		if ( ! in_array(Auth::user()->permissions, ['superadmin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}
		
		$account = Account::findOrFail($id);

		$user = $account->users()->where('permissions', 'admin')->firstOrFail();

		$data = [
			'items' => $this->accountsData,
			'back_url' => route('accounts.index'),
			'form_url' => route('accounts.update', ['id' => $id]),
			'form_method' => 'PUT',
			'title' => 'Cuenta "' . $account['name'] . '"',
		];

		foreach ($data['items'] as $key => &$itemGroup) {
			foreach ($itemGroup as $key => &$itemSubroup) {
				foreach ($itemSubroup as $itemName => &$item) {
					if (isset($account->$itemName)) {
						$item['value'] = $account->$itemName;
					}
					if (isset($user->$itemName)) {
						$item['value'] = $user->$itemName;
					}
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
		if ( ! in_array(Auth::user()->permissions, ['superadmin'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('dashboard');
		}

		$validation = [];

		foreach ($this->accountsData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation']) and ( ! isset($item['not_updatable']) or ! $item['not_updatable'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$this->validate($request, $validation);
		
		$account = Account::findOrFail($id);

		$account->professionals_limit = $request->professionals_limit;
		$account->patients_limit = $request->patients_limit;
		$account->administrator_limit = $request->administrator_limit;

		$account->save();

		$user = $account->users()->where('id', $id)->where('permissions', 'admin')->first();

		$user->name = $request->name;
		if ( ! empty($request->password)) {
			$user->password = Hash::make($request->password);
		}

		$user->save();

		$request->session()->flash('success', 'Se editaron con éxito los datos.');

		return redirect()->route('accounts.index');
	}
}
