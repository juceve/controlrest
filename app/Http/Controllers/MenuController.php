<?php

namespace App\Http\Controllers;

use App\Models\Detallemenu;
use App\Models\Evento;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Tipomenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class MenuController
 * @package App\Http\Controllers
 */
class MenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:menus.index')->only('index');
        $this->middleware('can:menus.destroy')->only('destroy');
    }

    public function index()
    {
        
        $sucursale_id = Auth::user()->sucursale_id;
        if($sucursale_id){
            $menus = Menu::where('sucursale_id',$sucursale_id)->get();
        }else{
           $menus = Menu::get(); 
        }
        return view('menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $menu = new Menu();
        $tipos = Tipomenu::all()->pluck('nombre', 'id');
        return view('menu.create', compact('menu', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Menu::$rules);

        $menu = Menu::create($request->all());

        return redirect()->route('menus.index')
            ->with('success', 'Menu created successfully.');
    }


    public function show($id)
    {
        $menu = Menu::find($id);
        $itemsmenu = Detallemenu::where('menu_id', $id)->get();
        return view('menu.show', compact('menu', 'itemsmenu'));
    }


    public function edit($id)
    {
        $menu = Menu::find($id);

        return view('menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        request()->validate(Menu::$rules);

        $menu->update($request->all());

        return redirect()->route('menus.index')
            ->with('success', 'Menu updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Detallemenu::where('menu_id', $id)->delete();
            $menu = Menu::find($id)->delete();
            DB::commit();
            return redirect()->route('menus.index')
                ->with('success', 'Menu eliminado correctamente');
        } catch (\Throwable $th) {

            DB::rollback();
            return redirect()->route('menus.index')
                ->with('error', 'No se elimino el Menu');
        }
    }

    public function menusemanal()
    {
        return view('menu.menusemanal');
    }
}
