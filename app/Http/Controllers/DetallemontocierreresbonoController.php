<?php

namespace App\Http\Controllers;

use App\Models\Detallemontocierreresbono;
use Illuminate\Http\Request;

/**
 * Class DetallemontocierreresbonoController
 * @package App\Http\Controllers
 */
class DetallemontocierreresbonoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detallemontocierreresbonos = Detallemontocierreresbono::paginate();

        return view('detallemontocierreresbono.index', compact('detallemontocierreresbonos'))
            ->with('i', (request()->input('page', 1) - 1) * $detallemontocierreresbonos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detallemontocierreresbono = new Detallemontocierreresbono();
        return view('detallemontocierreresbono.create', compact('detallemontocierreresbono'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detallemontocierreresbono::$rules);

        $detallemontocierreresbono = Detallemontocierreresbono::create($request->all());

        return redirect()->route('detallemontocierreresbonos.index')
            ->with('success', 'Detallemontocierreresbono created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detallemontocierreresbono = Detallemontocierreresbono::find($id);

        return view('detallemontocierreresbono.show', compact('detallemontocierreresbono'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detallemontocierreresbono = Detallemontocierreresbono::find($id);

        return view('detallemontocierreresbono.edit', compact('detallemontocierreresbono'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detallemontocierreresbono $detallemontocierreresbono
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detallemontocierreresbono $detallemontocierreresbono)
    {
        request()->validate(Detallemontocierreresbono::$rules);

        $detallemontocierreresbono->update($request->all());

        return redirect()->route('detallemontocierreresbonos.index')
            ->with('success', 'Detallemontocierreresbono updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detallemontocierreresbono = Detallemontocierreresbono::find($id)->delete();

        return redirect()->route('detallemontocierreresbonos.index')
            ->with('success', 'Detallemontocierreresbono deleted successfully');
    }
}
