<?php

namespace App\Http\Controllers;

use App\Models\Nivelcurso;
use App\Models\Ventasconfig;
use Illuminate\Http\Request;

/**
 * Class VentasconfigController
 * @package App\Http\Controllers
 */
class VentasconfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ventasconfigs.index')->only('index');
        $this->middleware('can:ventasconfigs.create')->only('create', 'store');
        $this->middleware('can:ventasconfigs.edit')->only('edit', 'update');        
    }
    public function index()
    {
        $ventasconfigs = Ventasconfig::paginate();

        return view('ventasconfig.index', compact('ventasconfigs'))
            ->with('i', (request()->input('page', 1) - 1) * $ventasconfigs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ventasconfig = new Ventasconfig();
        $niveles = Nivelcurso::all()->pluck('nombre','id');
        return view('ventasconfig.create', compact('ventasconfig', 'niveles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Ventasconfig::$rules);

        $ventasconfig = Ventasconfig::create($request->all());

        return redirect()->route('ventasconfigs.index')
            ->with('success', 'Ventasconfig created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ventasconfig = Ventasconfig::find($id);

        return view('ventasconfig.show', compact('ventasconfig'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ventasconfig = Ventasconfig::find($id);
        $niveles = Nivelcurso::all()->pluck('nombre','id');
        return view('ventasconfig.edit', compact('ventasconfig','niveles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Ventasconfig $ventasconfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ventasconfig $ventasconfig)
    {
        request()->validate(Ventasconfig::$rules);

        $ventasconfig->update($request->all());

        return redirect()->route('ventasconfigs.index')
            ->with('success', 'Ventasconfig updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $ventasconfig = Ventasconfig::find($id)->delete();

        return redirect()->route('ventasconfigs.index')
            ->with('success', 'Ventasconfig deleted successfully');
    }
}
