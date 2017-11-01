<?php

namespace App\Http\Controllers;

use DB;

class AdminController extends Controller
{
	function __construct() {
		DB::raw("SET time_zone='America/Argentina/Buenos_Aires'");
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
