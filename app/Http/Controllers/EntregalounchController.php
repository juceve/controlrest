<?php

namespace App\Http\Controllers;

use App\Models\Entregalounch;
use Illuminate\Http\Request;

/**
 * Class EntregalounchController
 * @package App\Http\Controllers
 */
class EntregalounchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entregalounches = Entregalounch::paginate();

        return view('entregalounch.index', compact('entregalounches'))
            ->with('i', (request()->input('page', 1) - 1) * $entregalounches->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entregalounch = new Entregalounch();
        return view('entregalounch.create', compact('entregalounch'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Entregalounch::$rules);

        $entregalounch = Entregalounch::create($request->all());

        return redirect()->route('entregalounches.index')
            ->with('success', 'Entregalounch created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entregalounch = Entregalounch::find($id);

        return view('entregalounch.show', compact('entregalounch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $entregalounch = Entregalounch::find($id);

        return view('entregalounch.edit', compact('entregalounch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Entregalounch $entregalounch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entregalounch $entregalounch)
    {
        request()->validate(Entregalounch::$rules);

        $entregalounch->update($request->all());

        return redirect()->route('entregalounches.index')
            ->with('success', 'Entregalounch updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $entregalounch = Entregalounch::find($id)->delete();

        return redirect()->route('entregalounches.index')
            ->with('success', 'Entregalounch deleted successfully');
    }
}
