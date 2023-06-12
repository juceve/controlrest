<?php

namespace App\Http\Controllers;

use App\Models\Catitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CatitemController extends Controller
{
    
    public function index()
    {
        $catitems = Catitem::all();

        return view('catitem.index', compact('catitems'));
    }

   
    public function create()
    {
        $catitem = new Catitem();
        return view('catitem.create', compact('catitem'));
    }

   
    public function store(Request $request)
    {
        request()->validate(Catitem::$rules);

        $catitem = Catitem::create($request->all());

        return redirect()->route('catitems.index')
            ->with('success', 'Catitem created successfully.');
    }

   
    public function show($id)
    {
        $catitem = Catitem::find($id);

        return view('catitem.show', compact('catitem'));
    }

    
    public function edit($id)
    {
        $catitem = Catitem::find($id);

        return view('catitem.edit', compact('catitem'));
    }

   
    public function update(Request $request, Catitem $catitem)
    {
        request()->validate(Catitem::$rules);

        $catitem->update($request->all());

        return redirect()->route('catitems.index')
            ->with('success', 'Catitem updated successfully');
    }

  
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $catitem = Catitem::find($id)->delete();
            DB::commit();
            return redirect()->route('catitems.index')
                ->with('success', 'Catitem deleted successfully');
        } catch (\Throwable $th) {

            DB::rollback();
            return redirect()->route('catitems.index')
                ->with('error', 'Tiene productos vinculados');
        }
    }
}
