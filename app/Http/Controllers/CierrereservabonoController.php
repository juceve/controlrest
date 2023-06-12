<?php

namespace App\Http\Controllers;

use App\Models\Cierrereservabono;
use Illuminate\Http\Request;

/**
 * Class CierrereservabonoController
 * @package App\Http\Controllers
 */
class CierrereservabonoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cierrereservabonos = Cierrereservabono::paginate();

        return view('cierrereservabono.index', compact('cierrereservabonos'))
            ->with('i', (request()->input('page', 1) - 1) * $cierrereservabonos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cierrereservabono = new Cierrereservabono();
        return view('cierrereservabono.create', compact('cierrereservabono'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Cierrereservabono::$rules);

        $cierrereservabono = Cierrereservabono::create($request->all());

        return redirect()->route('cierrereservabonos.index')
            ->with('success', 'Cierrereservabono created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cierrereservabono = Cierrereservabono::find($id);

        return view('cierrereservabono.show', compact('cierrereservabono'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cierrereservabono = Cierrereservabono::find($id);

        return view('cierrereservabono.edit', compact('cierrereservabono'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Cierrereservabono $cierrereservabono
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cierrereservabono $cierrereservabono)
    {
        request()->validate(Cierrereservabono::$rules);

        $cierrereservabono->update($request->all());

        return redirect()->route('cierrereservabonos.index')
            ->with('success', 'Cierrereservabono updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $cierrereservabono = Cierrereservabono::find($id)->delete();

        return redirect()->route('cierrereservabonos.index')
            ->with('success', 'Cierrereservabono deleted successfully');
    }
}
