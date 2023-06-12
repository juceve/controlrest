<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;


class MonedaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:monedas.index')->only('index');
        $this->middleware('can:monedas.create')->only('create', 'store');
        $this->middleware('can:monedas.edit')->only('edit', 'update');
        $this->middleware('can:monedas.destroy')->only('destroy');
    }

    public function index()
    {
        $monedas = Moneda::paginate();

        return view('moneda.index', compact('monedas'))
            ->with('i', (request()->input('page', 1) - 1) * $monedas->perPage());
    }


    public function create()
    {
        $moneda = new Moneda();
        return view('moneda.create', compact('moneda'));
    }

    public function store(Request $request)
    {
        request()->validate(Moneda::$rules);

        $moneda = Moneda::create($request->all());

        return redirect()->route('monedas.index')
            ->with('success', 'Moneda creada correctamente.');
    }

 
    public function show($id)
    {
        $moneda = Moneda::find($id);

        return view('moneda.show', compact('moneda'));
    }


    public function edit($id)
    {
        $moneda = Moneda::find($id);

        return view('moneda.edit', compact('moneda'));
    }

    public function update(Request $request, Moneda $moneda)
    {
        request()->validate(Moneda::$rules);

        $moneda->update($request->all());

        return redirect()->route('monedas.index')
            ->with('success', 'Moneda actualizada correctamente');
    }

    public function destroy($id)
    {
        $moneda = Moneda::find($id)->delete();

        return redirect()->route('monedas.index')
            ->with('success', 'Moneda eliminada correctamente');
    }
}
