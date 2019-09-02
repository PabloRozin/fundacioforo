<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use Storage;
use Response;

class FileController extends AdminController
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
        $path = "/hc/$name";
        echo Storage::put($path, File::get($file->getRealPath()));

        return Response::json('success', 200);
    }
}
