<?php

namespace App\Http\Controllers;

use App\Models\Catproducto;
use Illuminate\Http\Request;

/**
 * Class CatproductoController
 * @package App\Http\Controllers
 */
class CatproductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catproductos = Catproducto::paginate();

        return view('catproducto.index', compact('catproductos'))
            ->with('i', (request()->input('page', 1) - 1) * $catproductos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catproducto = new Catproducto();
        return view('catproducto.create', compact('catproducto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Catproducto::$rules);

        $catproducto = Catproducto::create($request->all());

        return redirect()->route('catproductos.index')
            ->with('success', 'Catproducto created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $catproducto = Catproducto::find($id);

        return view('catproducto.show', compact('catproducto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $catproducto = Catproducto::find($id);

        return view('catproducto.edit', compact('catproducto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Catproducto $catproducto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Catproducto $catproducto)
    {
        request()->validate(Catproducto::$rules);

        $catproducto->update($request->all());

        return redirect()->route('catproductos.index')
            ->with('success', 'Catproducto updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $catproducto = Catproducto::find($id)->delete();

        return redirect()->route('catproductos.index')
            ->with('success', 'Catproducto deleted successfully');
    }
}
