<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Patient;
use App\Professional;
use App\PatientAdmision;
use App\HCDate;
use Auth;
use File;
use Storage;
use Response;

class FileController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validation = ['file' => 'file|max:3000'];

		$this->validate($request, $validation);

		$file = $request->file('file');
		$name = $file->getClientOriginalName();
		$path = "$name";
		Storage::put($path, File::get($file->getRealPath()));

		return Response::json('success', 200);
	}
}
