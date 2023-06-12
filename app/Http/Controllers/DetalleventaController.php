<?php

namespace App\Http\Controllers;

use App\Models\Detalleventa;
use Illuminate\Http\Request;

/**
 * Class DetalleventaController
 * @package App\Http\Controllers
 */
class DetalleventaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalleventas = Detalleventa::paginate();

        return view('detalleventa.index', compact('detalleventas'))
            ->with('i', (request()->input('page', 1) - 1) * $detalleventas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detalleventa = new Detalleventa();
        return view('detalleventa.create', compact('detalleventa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detalleventa::$rules);

        $detalleventa = Detalleventa::create($request->all());

        return redirect()->route('detalleventas.index')
            ->with('success', 'Detalleventa created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detalleventa = Detalleventa::find($id);

        return view('detalleventa.show', compact('detalleventa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detalleventa = Detalleventa::find($id);

        return view('detalleventa.edit', compact('detalleventa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detalleventa $detalleventa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detalleventa $detalleventa)
    {
        request()->validate(Detalleventa::$rules);

        $detalleventa->update($request->all());

        return redirect()->route('detalleventas.index')
            ->with('success', 'Detalleventa updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detalleventa = Detalleventa::find($id)->delete();

        return redirect()->route('detalleventas.index')
            ->with('success', 'Detalleventa deleted successfully');
    }
}
