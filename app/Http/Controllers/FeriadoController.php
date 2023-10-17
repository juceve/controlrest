<?php

namespace App\Http\Controllers;

use App\Models\Feriado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FeriadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:feriados.index')->only('index');
        $this->middleware('can:feriados.create')->only('create', 'store');
        $this->middleware('can:feriados.edit')->only('edit', 'update');
        $this->middleware('can:feriados.destroy')->only('destroy');
    }

    public function index()
    {
        $feriados = Feriado::where('sucursale_id',Auth::user()->sucursale_id)
        ->orderBy('id','DESC')
        ->get();

        return view('feriado.index', compact('feriados'));
    }

     public function create()
    {
        $feriado = new Feriado();
        return view('feriado.create', compact('feriado'));
    }

    public function store(Request $request)
    {
        request()->validate(Feriado::$rules);

        $feriado = Feriado::create($request->all());
        $cambios = cambiosPorFeriado($request->fecha);
        return redirect()->route('feriados.index')
            ->with('success', 'Feriado creado correctamente. Cambios: '. $cambios);
    }

    public function show($id)
    {
        $feriado = Feriado::find($id);

        return view('feriado.show', compact('feriado'));
    }

    public function edit($id)
    {
        $feriado = Feriado::find($id);

        return view('feriado.edit', compact('feriado'));
    }

    public function update(Request $request, Feriado $feriado)
    {
        request()->validate(Feriado::$rules);

        $feriado->update($request->all());

        return redirect()->route('feriados.index')
            ->with('success', 'Feriado editado correctamente');
    }

    public function destroy($id)
    {
        $feriado = Feriado::find($id)->delete();

        return redirect()->route('feriados.index')
            ->with('success', 'Feriado eliminado correctamente');
    }
}
