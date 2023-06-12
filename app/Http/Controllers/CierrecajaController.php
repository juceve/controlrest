<?php

namespace App\Http\Controllers;

use App\Models\Cierrecaja;
use Illuminate\Http\Request;

/**
 * Class CierrecajaController
 * @package App\Http\Controllers
 */
class CierrecajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cierrecajas = Cierrecaja::paginate();

        return view('cierrecaja.index', compact('cierrecajas'))
            ->with('i', (request()->input('page', 1) - 1) * $cierrecajas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cierrecaja = new Cierrecaja();
        return view('cierrecaja.create', compact('cierrecaja'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Cierrecaja::$rules);

        $cierrecaja = Cierrecaja::create($request->all());

        return redirect()->route('cierrecajas.index')
            ->with('success', 'Cierrecaja created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cierrecaja = Cierrecaja::find($id);

        return view('cierrecaja.show', compact('cierrecaja'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cierrecaja = Cierrecaja::find($id);

        return view('cierrecaja.edit', compact('cierrecaja'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Cierrecaja $cierrecaja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cierrecaja $cierrecaja)
    {
        request()->validate(Cierrecaja::$rules);

        $cierrecaja->update($request->all());

        return redirect()->route('cierrecajas.index')
            ->with('success', 'Cierrecaja updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $cierrecaja = Cierrecaja::find($id)->delete();

        return redirect()->route('cierrecajas.index')
            ->with('success', 'Cierrecaja deleted successfully');
    }
}
