<?php

namespace App\Http\Controllers;

use App\Models\Creditoprofesore;
use Illuminate\Http\Request;

/**
 * Class CreditoprofesoreController
 * @package App\Http\Controllers
 */
class CreditoprofesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $creditoprofesores = Creditoprofesore::paginate();

        return view('creditoprofesore.index', compact('creditoprofesores'))
            ->with('i', (request()->input('page', 1) - 1) * $creditoprofesores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $creditoprofesore = new Creditoprofesore();
        return view('creditoprofesore.create', compact('creditoprofesore'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Creditoprofesore::$rules);

        $creditoprofesore = Creditoprofesore::create($request->all());

        return redirect()->route('creditoprofesores.index')
            ->with('success', 'Creditoprofesore created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $creditoprofesore = Creditoprofesore::find($id);

        return view('creditoprofesore.show', compact('creditoprofesore'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $creditoprofesore = Creditoprofesore::find($id);

        return view('creditoprofesore.edit', compact('creditoprofesore'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Creditoprofesore $creditoprofesore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Creditoprofesore $creditoprofesore)
    {
        request()->validate(Creditoprofesore::$rules);

        $creditoprofesore->update($request->all());

        return redirect()->route('creditoprofesores.index')
            ->with('success', 'Creditoprofesore updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $creditoprofesore = Creditoprofesore::find($id)->delete();

        return redirect()->route('creditoprofesores.index')
            ->with('success', 'Creditoprofesore deleted successfully');
    }
}
