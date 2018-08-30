<?php

namespace App\Http\Controllers;

use DB;
use App\Account;
use Auth;

class AdminController extends Controller
{
	public $account;
	public $data;

	function __construct() {
		$this->account = Account::where('id', Auth::user()->account_id)->firstOrFail();

		if ($this->account->state === 0) {
			Auth::logout();
		}

		if ($this->account->accepted_conditions === 0) {
			redirect()->route('accept_conditions');
		}
	}

	function adminIndex() {

	}

	function adminCreate() {
		
	}

	function adminStore() {
		
	}

	function adminEdit() {
		
	}

	function adminUpdate() {

	}
}
