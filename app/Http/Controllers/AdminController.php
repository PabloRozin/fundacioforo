<?php

namespace App\Http\Controllers;

use DB;
use App\Account;
use Auth;

class AdminController extends Controller
{
	public $account;

	function __construct() {
		$this->account = Account::where('id', Auth::user()->account_id)->firstOrFail();
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
