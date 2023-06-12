<?php

namespace App\Http\Controllers;

use App\Models\Bonomensuale;
use Illuminate\Http\Request;

/**
 * Class BonomensualeController
 * @package App\Http\Controllers
 */
class BonomensualeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonomensuales = Bonomensuale::paginate();

        return view('bonomensuale.index', compact('bonomensuales'))
            ->with('i', (request()->input('page', 1) - 1) * $bonomensuales->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bonomensuale = new Bonomensuale();
        return view('bonomensuale.create', compact('bonomensuale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Bonomensuale::$rules);

        $bonomensuale = Bonomensuale::create($request->all());

        return redirect()->route('bonomensuales.index')
            ->with('success', 'Bonomensuale created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bonomensuale = Bonomensuale::find($id);

        return view('bonomensuale.show', compact('bonomensuale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bonomensuale = Bonomensuale::find($id);

        return view('bonomensuale.edit', compact('bonomensuale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Bonomensuale $bonomensuale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bonomensuale $bonomensuale)
    {
        request()->validate(Bonomensuale::$rules);

        $bonomensuale->update($request->all());

        return redirect()->route('bonomensuales.index')
            ->with('success', 'Bonomensuale updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $bonomensuale = Bonomensuale::find($id)->delete();

        return redirect()->route('bonomensuales.index')
            ->with('success', 'Bonomensuale deleted successfully');
    }
}
