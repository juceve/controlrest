<?php

namespace App\Http\Controllers;

use App\Models\Detallecierrereservabono;
use Illuminate\Http\Request;

/**
 * Class DetallecierrereservabonoController
 * @package App\Http\Controllers
 */
class DetallecierrereservabonoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detallecierrereservabonos = Detallecierrereservabono::paginate();

        return view('detallecierrereservabono.index', compact('detallecierrereservabonos'))
            ->with('i', (request()->input('page', 1) - 1) * $detallecierrereservabonos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detallecierrereservabono = new Detallecierrereservabono();
        return view('detallecierrereservabono.create', compact('detallecierrereservabono'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detallecierrereservabono::$rules);

        $detallecierrereservabono = Detallecierrereservabono::create($request->all());

        return redirect()->route('detallecierrereservabonos.index')
            ->with('success', 'Detallecierrereservabono created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detallecierrereservabono = Detallecierrereservabono::find($id);

        return view('detallecierrereservabono.show', compact('detallecierrereservabono'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detallecierrereservabono = Detallecierrereservabono::find($id);

        return view('detallecierrereservabono.edit', compact('detallecierrereservabono'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detallecierrereservabono $detallecierrereservabono
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detallecierrereservabono $detallecierrereservabono)
    {
        request()->validate(Detallecierrereservabono::$rules);

        $detallecierrereservabono->update($request->all());

        return redirect()->route('detallecierrereservabonos.index')
            ->with('success', 'Detallecierrereservabono updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detallecierrereservabono = Detallecierrereservabono::find($id)->delete();

        return redirect()->route('detallecierrereservabonos.index')
            ->with('success', 'Detallecierrereservabono deleted successfully');
    }
}
