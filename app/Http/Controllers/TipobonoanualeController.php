<?php

namespace App\Http\Controllers;

use App\Models\Tipobonoanuale;
use Illuminate\Http\Request;

/**
 * Class TipobonoanualeController
 * @package App\Http\Controllers
 */
class TipobonoanualeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipobonoanuales = Tipobonoanuale::paginate();

        return view('tipobonoanuale.index', compact('tipobonoanuales'))
            ->with('i', (request()->input('page', 1) - 1) * $tipobonoanuales->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipobonoanuale = new Tipobonoanuale();
        return view('tipobonoanuale.create', compact('tipobonoanuale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tipobonoanuale::$rules);

        $tipobonoanuale = Tipobonoanuale::create($request->all());

        return redirect()->route('tipobonoanuales.index')
            ->with('success', 'Tipobonoanuale created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipobonoanuale = Tipobonoanuale::find($id);

        return view('tipobonoanuale.show', compact('tipobonoanuale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipobonoanuale = Tipobonoanuale::find($id);

        return view('tipobonoanuale.edit', compact('tipobonoanuale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tipobonoanuale $tipobonoanuale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipobonoanuale $tipobonoanuale)
    {
        request()->validate(Tipobonoanuale::$rules);

        $tipobonoanuale->update($request->all());

        return redirect()->route('tipobonoanuales.index')
            ->with('success', 'Tipobonoanuale updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tipobonoanuale = Tipobonoanuale::find($id)->delete();

        return redirect()->route('tipobonoanuales.index')
            ->with('success', 'Tipobonoanuale deleted successfully');
    }
}
