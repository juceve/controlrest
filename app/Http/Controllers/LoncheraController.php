<?php

namespace App\Http\Controllers;

use App\Models\Lonchera;
use Illuminate\Http\Request;

/**
 * Class LoncheraController
 * @package App\Http\Controllers
 */
class LoncheraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loncheras = Lonchera::paginate();

        return view('lonchera.index', compact('loncheras'))
            ->with('i', (request()->input('page', 1) - 1) * $loncheras->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lonchera = new Lonchera();
        return view('lonchera.create', compact('lonchera'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Lonchera::$rules);

        $lonchera = Lonchera::create($request->all());

        return redirect()->route('loncheras.index')
            ->with('success', 'Lonchera created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lonchera = Lonchera::find($id);

        return view('lonchera.show', compact('lonchera'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lonchera = Lonchera::find($id);

        return view('lonchera.edit', compact('lonchera'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Lonchera $lonchera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lonchera $lonchera)
    {
        request()->validate(Lonchera::$rules);

        $lonchera->update($request->all());

        return redirect()->route('loncheras.index')
            ->with('success', 'Lonchera updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $lonchera = Lonchera::find($id)->delete();

        return redirect()->route('loncheras.index')
            ->with('success', 'Lonchera deleted successfully');
    }
}
