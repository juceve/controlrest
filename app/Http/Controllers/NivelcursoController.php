<?php

namespace App\Http\Controllers;

use App\Models\Nivelcurso;
use App\Models\Sucursale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NivelcursoController extends Controller
{

    public function index()
    {
        $sucursal = Auth::user()->sucursale_id;
        $nivelcursos = null;
        if($sucursal){
            $nivelcursos = Nivelcurso::where('sucursale_id',$sucursal)->paginate(10);
        }else{
            $nivelcursos = Nivelcurso::paginate(10);
        }
        

        return view('nivelcurso.index', compact('nivelcursos'))
            ->with('i', (request()->input('page', 1) - 1) * $nivelcursos->perPage());
    }

    public function create()
    {
        $sucursales = Sucursale::all()->pluck('nombre','id');
        $nivelcurso = new Nivelcurso();
        return view('nivelcurso.create', compact('nivelcurso','sucursales'));
    }

    public function store(Request $request)
    {
        request()->validate(Nivelcurso::$rules);

        $nivelcurso = Nivelcurso::create($request->all());

        return redirect()->route('nivelcursos.index')
            ->with('success', 'Nivel creado correctamente.');
    }


    public function show($id)
    {
        $nivelcurso = Nivelcurso::find($id);

        return view('nivelcurso.show', compact('nivelcurso'));
    }

    public function edit($id)
    {
        $sucursales = Sucursale::all()->pluck('nombre','id');
        $nivelcurso = Nivelcurso::find($id);

        return view('nivelcurso.edit', compact('nivelcurso','sucursales'));
    }

    public function update(Request $request, Nivelcurso $nivelcurso)
    {
        request()->validate(Nivelcurso::$rules);

        $nivelcurso->update($request->all());

        return redirect()->route('nivelcursos.index')
            ->with('success', 'Nivel editado correctamente');
    }

    public function destroy($id)
    {
        $nivelcurso = Nivelcurso::find($id)->delete();

        return redirect()->route('nivelcursos.index')
            ->with('success', 'Nivel eliminado correctamente');
    }
}
