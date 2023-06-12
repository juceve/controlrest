<?php

namespace App\Http\Controllers;

use App\Models\Detallemontocierre;
use Illuminate\Http\Request;

/**
 * Class DetallemontocierreController
 * @package App\Http\Controllers
 */
class DetallemontocierreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detallemontocierres = Detallemontocierre::paginate();

        return view('detallemontocierre.index', compact('detallemontocierres'))
            ->with('i', (request()->input('page', 1) - 1) * $detallemontocierres->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detallemontocierre = new Detallemontocierre();
        return view('detallemontocierre.create', compact('detallemontocierre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detallemontocierre::$rules);

        $detallemontocierre = Detallemontocierre::create($request->all());

        return redirect()->route('detallemontocierres.index')
            ->with('success', 'Detallemontocierre created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detallemontocierre = Detallemontocierre::find($id);

        return view('detallemontocierre.show', compact('detallemontocierre'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detallemontocierre = Detallemontocierre::find($id);

        return view('detallemontocierre.edit', compact('detallemontocierre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detallemontocierre $detallemontocierre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detallemontocierre $detallemontocierre)
    {
        request()->validate(Detallemontocierre::$rules);

        $detallemontocierre->update($request->all());

        return redirect()->route('detallemontocierres.index')
            ->with('success', 'Detallemontocierre updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detallemontocierre = Detallemontocierre::find($id)->delete();

        return redirect()->route('detallemontocierres.index')
            ->with('success', 'Detallemontocierre deleted successfully');
    }
}
