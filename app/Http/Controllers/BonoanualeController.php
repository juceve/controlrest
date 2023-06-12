<?php

namespace App\Http\Controllers;

use App\Models\Bonoanuale;
use Illuminate\Http\Request;

/**
 * Class BonoanualeController
 * @package App\Http\Controllers
 */
class BonoanualeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonoanuales = Bonoanuale::paginate();

        return view('bonoanuale.index', compact('bonoanuales'))
            ->with('i', (request()->input('page', 1) - 1) * $bonoanuales->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bonoanuale = new Bonoanuale();
        return view('bonoanuale.create', compact('bonoanuale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Bonoanuale::$rules);

        $bonoanuale = Bonoanuale::create($request->all());

        return redirect()->route('bonoanuales.index')
            ->with('success', 'Bonoanuale created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bonoanuale = Bonoanuale::find($id);

        return view('bonoanuale.show', compact('bonoanuale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bonoanuale = Bonoanuale::find($id);

        return view('bonoanuale.edit', compact('bonoanuale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Bonoanuale $bonoanuale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bonoanuale $bonoanuale)
    {
        request()->validate(Bonoanuale::$rules);

        $bonoanuale->update($request->all());

        return redirect()->route('bonoanuales.index')
            ->with('success', 'Bonoanuale updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $bonoanuale = Bonoanuale::find($id)->delete();

        return redirect()->route('bonoanuales.index')
            ->with('success', 'Bonoanuale deleted successfully');
    }
}
