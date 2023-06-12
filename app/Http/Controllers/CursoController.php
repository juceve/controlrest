<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Nivelcurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{

    public function index()
    {
        $sucursal = Auth::user()->sucursale_id;
        $sql = "SELECT c.*, n.nombre nivel, n.sucursale_id FROM cursos c
        INNER JOIN nivelcursos n on n.id = c.nivelcurso_id";
        if($sucursal!= ""){
            $sql = $sql." WHERE sucursale_id = $sucursal";
        }
        
        $cursos = DB::select($sql);

        return view('curso.index', compact('cursos'));
    }

 
    public function create()
    {
        $niveles = Nivelcurso::where('sucursale_id',Auth::user()->sucursale_id)->get()->pluck('nombre','id');
        $curso = new Curso();
        return view('curso.create', compact('curso','niveles'));
    }
   
    public function store(Request $request)
    {
        request()->validate(Curso::$rules);

        $curso = Curso::create($request->all());

        return redirect()->route('cursos.create')
            ->with('success', 'Curso registrado correctamente.');
    }

    public function show($id)
    {
        $curso = Curso::find($id);

        return view('curso.show', compact('curso'));
    }

    public function edit($id)
    {
        $niveles = Nivelcurso::where('sucursale_id',Auth::user()->sucursale_id)->get()->pluck('nombre','id');
        $curso = Curso::find($id);

        return view('curso.edit', compact('curso','niveles'));
    }

    public function update(Request $request, Curso $curso)
    {
        request()->validate(Curso::$rules);

        $curso->update($request->all());

        return redirect()->route('cursos.index')
            ->with('success', 'Curso editado correctamente');
    }

    public function destroy($id)
    {
        $curso = Curso::find($id)->delete();

        return redirect()->route('cursos.index')
            ->with('success', 'Curso deleted successfully');
    }
}
