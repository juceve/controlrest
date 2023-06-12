<?php

namespace App\Http\Controllers;

use App\Models\Catitem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;


class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:items.index')->only('index');
        $this->middleware('can:items.edit')->only('edit', 'update');
        $this->middleware('can:items.create')->only('create', 'store');
        $this->middleware('can:items.destroy')->only('destroy');
    }

    public function index()
    {
        $sucursale_id = Auth::user()->sucursale_id;
        if(!is_null($sucursale_id)){
            $items = Item::where('sucursale_id',$sucursale_id)->get();
        }else{
            $items = Item::all();
        }       
        
        return view('item.index', compact('items'))
            ->with('i', 0);
    }

    public function create()
    {
        $item = new Item();
        $categorias = Catitem::all()->pluck('nombre', 'id');
        return view('item.create', compact('item', 'categorias'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            request()->validate(Item::$rules);

            $item = Item::create($request->all());

            $file = $request->file('imagen');
            if ($file) {
                $extension =  $file->clientExtension();
                $path = $file->storeAs(
                    'img/productos',
                    $item->id . ".$extension"
                );
                $item->imagen = $path;
                $item->save();
            } else {
                $item->imagen = 'img/noImagen.jpg';
                $item->save();
            }

            DB::commit();
            return redirect()->route('items.create')
                ->with('success', 'Producto creado correctamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('items.create')
                ->with('error', 'Ocurrio un error.');
        }
    }

    public function show($id)
    {
        $item = Item::find($id);

        return view('item.show', compact('item'));
    }


    public function edit($id)
    {
        $item = Item::find($id);
        $categorias = Catitem::all()->pluck('nombre', 'id');
        return view('item.edit', compact('item', 'categorias'));
    }


    public function update(Request $request, Item $item)
    {
        request()->validate(Item::$rules);
        try {
            $item->update($request->all());
            $file = $request->file('imagen');
            if ($file) {
                $extension =  $file->clientExtension();
                $path = $file->storeAs(
                    'img/productos',
                    $item->id . ".$extension"
                );
                $item->imagen = $path;
                $item->save();
            }          

            return redirect()->route('items.index')
                ->with('success', 'Producto actualizado correctamente!');
        } catch (\Throwable $th) {
            return redirect()->route('items.edit', $item)
                ->with('error', 'Ha ocurrido un error');
        }
    }


    public function destroy($id)
    {
        try {
            $item = Item::find($id)->delete();

            return redirect()->route('items.index')
                ->with('success', 'Producto eliminado correctamente!');
        } catch (\Throwable $th) {
            return redirect()->route('items.index')
                ->with('error', 'Ha ocurrido un error');
        }
    }
}
