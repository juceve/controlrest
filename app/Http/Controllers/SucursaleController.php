<?php

namespace App\Http\Controllers;

use App\Models\Sucursale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SucursaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.sucursales.index')->only('index');
        $this->middleware('can:admin.sucursales.create')->only('create', 'store');
        $this->middleware('can:admin.sucursales.edit')->only('edit', 'update');
        $this->middleware('can:admin.sucursales.destroy')->only('destroy');
    }

    public function index()
    {        
        $sucursales = Sucursale::all();
        return view('admin.sucursale.index', compact('sucursales'));
    }

    public function create()
    {
        $sucursale = new Sucursale();
       
        return view('admin.sucursale.create', compact('sucursale'));
    }


    public function store(Request $request)
    {
        request()->validate(Sucursale::$rules);

        $sucursale = Sucursale::create($request->all());

        return redirect()->route('admin.sucursales.index')
            ->with('success', 'Sucursal creada correctamente.');
    }

    public function show($id)
    {
        $sucursale = Sucursale::find($id);

        return view('admin.sucursale.show', compact('sucursale'));
    }


    public function edit($id)
    {
        $sucursale = Sucursale::find($id);

        return view('admin.sucursale.edit', compact('sucursale'));
    }


    public function update(Request $request, Sucursale $sucursale)
    {
        request()->validate(Sucursale::$rules);

        $sucursale->update($request->all());

        return redirect()->route('admin.sucursales.index')
            ->with('success', 'Sucursal editada correctamente');
    }

 
    public function destroy($id)
    {
        $sucursale = Sucursale::find($id)->delete();

        return redirect()->route('admin.sucursales.index')
            ->with('success', 'Sucursal elminada correctamente.');
    }
}
