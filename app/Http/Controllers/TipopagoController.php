<?php

namespace App\Http\Controllers;

use App\Models\Tipopago;
use Illuminate\Http\Request;

/**
 * Class TipopagoController
 * @package App\Http\Controllers
 */
class TipopagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipopagos = Tipopago::paginate();

        return view('tipopago.index', compact('tipopagos'))
            ->with('i', (request()->input('page', 1) - 1) * $tipopagos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipopago = new Tipopago();
        return view('tipopago.create', compact('tipopago'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tipopago::$rules);

        $tipopago = Tipopago::create($request->all());

        return redirect()->route('settings')
            ->with('success', 'Tipopago creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipopago = Tipopago::find($id);

        return view('tipopago.show', compact('tipopago'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipopago = Tipopago::find($id);

        return view('tipopago.edit', compact('tipopago'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tipopago $tipopago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipopago $tipopago)
    {
        request()->validate(Tipopago::$rules);

        $tipopago->update($request->all());

        return redirect()->route('tipopagos.index')
            ->with('success', 'Tipopago updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tipopago = Tipopago::find($id)->delete();

        return redirect()->route('tipopagos.index')
            ->with('success', 'Tipopago deleted successfully');
    }
}
