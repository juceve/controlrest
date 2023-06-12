<?php

namespace App\Http\Controllers;

use App\Models\Tutore;
use Illuminate\Http\Request;

/**
 * Class TutoreController
 * @package App\Http\Controllers
 */
class TutoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tutores = Tutore::paginate();

        return view('tutore.index', compact('tutores'))
            ->with('i', (request()->input('page', 1) - 1) * $tutores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tutore = new Tutore();
        return view('tutore.create', compact('tutore'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tutore::$rules);

        $tutore = Tutore::create($request->all());

        return redirect()->route('tutores.index')
            ->with('success', 'Tutore created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tutore = Tutore::find($id);

        return view('tutore.show', compact('tutore'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tutore = Tutore::find($id);

        return view('tutore.edit', compact('tutore'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tutore $tutore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tutore $tutore)
    {
        request()->validate(Tutore::$rules);

        $tutore->update($request->all());

        return redirect()->route('tutores.index')
            ->with('success', 'Tutore updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tutore = Tutore::find($id)->delete();

        return redirect()->route('tutores.index')
            ->with('success', 'Tutore deleted successfully');
    }
}
