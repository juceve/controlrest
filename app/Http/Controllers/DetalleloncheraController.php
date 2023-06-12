<?php

namespace App\Http\Controllers;

use App\Models\Detallelonchera;
use Illuminate\Http\Request;

/**
 * Class DetalleloncheraController
 * @package App\Http\Controllers
 */
class DetalleloncheraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalleloncheras = Detallelonchera::paginate();

        return view('detallelonchera.index', compact('detalleloncheras'))
            ->with('i', (request()->input('page', 1) - 1) * $detalleloncheras->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detallelonchera = new Detallelonchera();
        return view('detallelonchera.create', compact('detallelonchera'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Detallelonchera::$rules);

        $detallelonchera = Detallelonchera::create($request->all());

        return redirect()->route('detalleloncheras.index')
            ->with('success', 'Detallelonchera created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detallelonchera = Detallelonchera::find($id);

        return view('detallelonchera.show', compact('detallelonchera'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detallelonchera = Detallelonchera::find($id);

        return view('detallelonchera.edit', compact('detallelonchera'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Detallelonchera $detallelonchera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detallelonchera $detallelonchera)
    {
        request()->validate(Detallelonchera::$rules);

        $detallelonchera->update($request->all());

        return redirect()->route('detalleloncheras.index')
            ->with('success', 'Detallelonchera updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $detallelonchera = Detallelonchera::find($id)->delete();

        return redirect()->route('detalleloncheras.index')
            ->with('success', 'Detallelonchera deleted successfully');
    }
}
