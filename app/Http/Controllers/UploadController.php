<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Livewire\Livewire;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // $file = $request->file;
        // Livewire::dispatchBrowserEvent('cargaFileInput', ['file' => $file]);
        dd($request);
    }
}
