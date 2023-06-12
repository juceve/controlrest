<?php

namespace App\Http\Controllers;

use App\Models\Bonofecha;
use Illuminate\Http\Request;

/**
 * Class BonofechaController
 * @package App\Http\Controllers
 */
class BonofechaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonofechas = Bonofecha::paginate();

        return view('bonofecha.index', compact('bonofechas'))
            ->with('i', (request()->input('page', 1) - 1) * $bonofechas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bonofecha = new Bonofecha();
        return view('bonofecha.create', compact('bonofecha'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Bonofecha::$rules);

        $bonofecha = Bonofecha::create($request->all());

        return redirect()->route('bonofechas.index')
            ->with('success', 'Bonofecha created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bonofecha = Bonofecha::find($id);

        return view('bonofecha.show', compact('bonofecha'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bonofecha = Bonofecha::find($id);

        return view('bonofecha.edit', compact('bonofecha'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Bonofecha $bonofecha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bonofecha $bonofecha)
    {
        request()->validate(Bonofecha::$rules);

        $bonofecha->update($request->all());

        return redirect()->route('bonofechas.index')
            ->with('success', 'Bonofecha updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $bonofecha = Bonofecha::find($id)->delete();

        return redirect()->route('bonofechas.index')
            ->with('success', 'Bonofecha deleted successfully');
    }
}
