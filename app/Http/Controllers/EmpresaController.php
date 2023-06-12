<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.empresas.index')->only('index');
        $this->middleware('can:admin.empresas.create')->only('create', 'store');
        $this->middleware('can:admin.empresas.edit')->only('edit', 'update');
        $this->middleware('can:admin.empresas.destroy')->only('destroy');
    }
    public function index()
    {
        $empresas = Empresa::paginate();

        return view('admin.empresa.index', compact('empresas'))
            ->with('i', (request()->input('page', 1) - 1) * $empresas->perPage());
    }

   
    public function create()
    {
        $empresa = new Empresa();
        return view('admin.empresa.create', compact('empresa'));
    }

    public function store(Request $request)
    {
        request()->validate(Empresa::$rules);

        $empresa = Empresa::create($request->all());

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa created successfully.');
    }

    public function show($id)
    {
        $empresa = Empresa::find($id);

        return view('admin.empresa.show', compact('empresa'));
    }

    public function edit($id)
    {
        $empresa = Empresa::find($id);

        return view('admin.empresa.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        request()->validate(Empresa::$rules);

        $empresa->update($request->all());

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa updated successfully');
    }

    public function destroy($id)
    {
        $empresa = Empresa::find($id)->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa deleted successfully');
    }
}
