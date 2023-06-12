<?php

namespace App\Http\Controllers;

use App\Models\Tipomenu;
use Illuminate\Http\Request;

/**
 * Class TipomenuController
 * @package App\Http\Controllers
 */
class TipomenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipomenus = Tipomenu::paginate();

        return view('tipomenu.index', compact('tipomenus'))
            ->with('i', (request()->input('page', 1) - 1) * $tipomenus->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipomenu = new Tipomenu();
        return view('tipomenu.create', compact('tipomenu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tipomenu::$rules);

        $tipomenu = Tipomenu::create($request->all());

        return redirect()->route('tipomenus.index')
            ->with('success', 'Tipomenu created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipomenu = Tipomenu::find($id);

        return view('tipomenu.show', compact('tipomenu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipomenu = Tipomenu::find($id);

        return view('tipomenu.edit', compact('tipomenu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tipomenu $tipomenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipomenu $tipomenu)
    {
        request()->validate(Tipomenu::$rules);

        $tipomenu->update($request->all());

        return redirect()->route('tipomenus.index')
            ->with('success', 'Tipomenu updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tipomenu = Tipomenu::find($id)->delete();

        return redirect()->route('tipomenus.index')
            ->with('success', 'Tipomenu deleted successfully');
    }
}
