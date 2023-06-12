<?php

namespace App\Http\Controllers;

use App\Models\Detallecierre;
use Illuminate\Http\Request;

/**
 * Class DetallecierreController
 * @package App\Http\Controllers
 */
class DetallecierreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detallecierres = Detallecierre::paginate();

        return view('detallecierre.index', compact('detallecierres'))
            ->with('i', (request()->input('page', 1) - 1) * $detallecierres->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detallecierre = new Detallecierre();
        return view('detallecierre.create', compact('detallecierre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detallecierre::$rules);

        $detallecierre = Detallecierre::create($request->all());

        return redirect()->route('detallecierres.index')
            ->with('success', 'Detallecierre created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detallecierre = Detallecierre::find($id);

        return view('detallecierre.show', compact('detallecierre'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detallecierre = Detallecierre::find($id);

        return view('detallecierre.edit', compact('detallecierre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detallecierre $detallecierre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detallecierre $detallecierre)
    {
        request()->validate(Detallecierre::$rules);

        $detallecierre->update($request->all());

        return redirect()->route('detallecierres.index')
            ->with('success', 'Detallecierre updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detallecierre = Detallecierre::find($id)->delete();

        return redirect()->route('detallecierres.index')
            ->with('success', 'Detallecierre deleted successfully');
    }
}
