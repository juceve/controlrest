<?php

namespace App\Http\Controllers;

use App\Models\Detalleevento;
use Illuminate\Http\Request;

/**
 * Class DetalleeventoController
 * @package App\Http\Controllers
 */
class DetalleeventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalleeventos = Detalleevento::paginate();

        return view('detalleevento.index', compact('detalleeventos'))
            ->with('i', (request()->input('page', 1) - 1) * $detalleeventos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detalleevento = new Detalleevento();
        return view('detalleevento.create', compact('detalleevento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detalleevento::$rules);

        $detalleevento = Detalleevento::create($request->all());

        return redirect()->route('detalleeventos.index')
            ->with('success', 'Detalleevento created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detalleevento = Detalleevento::find($id);

        return view('detalleevento.show', compact('detalleevento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detalleevento = Detalleevento::find($id);

        return view('detalleevento.edit', compact('detalleevento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detalleevento $detalleevento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detalleevento $detalleevento)
    {
        request()->validate(Detalleevento::$rules);

        $detalleevento->update($request->all());

        return redirect()->route('detalleeventos.index')
            ->with('success', 'Detalleevento updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detalleevento = Detalleevento::find($id)->delete();

        return redirect()->route('detalleeventos.index')
            ->with('success', 'Detalleevento deleted successfully');
    }
}
