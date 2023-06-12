<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use App\Models\Nivelcurso;
use App\Models\Preciomenu;
use App\Models\Tipomenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PreciomenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:precios.index')->only('index');
        $this->middleware('can:precios.create')->only('create', 'store');
        $this->middleware('can:precios.edit')->only('edit', 'update');
        $this->middleware('can:precios.destroy')->only('destroy');
    }

    public function index()
    {
        $preciomenus = Preciomenu::where('sucursale_id',Auth::user()->sucursale_id)->paginate();
        $moneda = Moneda::where('predeterminado',1)->first();
        return view('preciomenu.index', compact('preciomenus','moneda'))
            ->with('i', (request()->input('page', 1) - 1) * $preciomenus->perPage());
    }


    public function create()
    {
        $preciomenu = new Preciomenu();
        $tipos = Tipomenu::all()->pluck('nombre','id');
        $moneda = Moneda::where('predeterminado',1)->first();
        $niveles="";
        if (Auth::user()->sucursale_id != "") {
            $niveles = Nivelcurso::where('sucursale_id', Auth::user()->sucursale_id)->get()->pluck('nombre', 'id');                       
        } else {
            $niveles = Nivelcurso::all()->pluck('nombre', 'id');
        }
        return view('preciomenu.create', compact('preciomenu','tipos','niveles','moneda'));
    }

    public function store(Request $request)
    {
        request()->validate(Preciomenu::$rules);

        $preciomenu = Preciomenu::create($request->all());

        return redirect()->route('precios.index')
            ->with('success', 'Precio registrado correctamente.');
    }

    public function show($id)
    {
        $preciomenu = Preciomenu::find($id);
        $moneda = Moneda::where('predeterminado',1)->first();
        return view('preciomenu.show', compact('preciomenu','moneda'));
    }


    public function edit($id)
    {
        $preciomenu = Preciomenu::find($id);
        $moneda = Moneda::where('predeterminado',1)->first();
        $tipos = Tipomenu::all()->pluck('nombre','id');
        $niveles="";
        if (Auth::user()->sucursale_id != "") {
            $niveles = Nivelcurso::where('sucursale_id', Auth::user()->sucursale_id)->get()->pluck('nombre', 'id');                       
        } else {
            $niveles = Nivelcurso::all()->pluck('nombre', 'id');
        }
        return view('preciomenu.edit', compact('preciomenu', 'tipos', 'niveles','moneda'));
    }


    public function update(Request $request, Preciomenu $precio)
    {
        request()->validate(Preciomenu::$rules);
        $preciomenu = Preciomenu::find($precio->id);
        $preciomenu->nivelcurso_id = $request->nivelcurso_id;
        $preciomenu->tipomenu_id = $request->tipomenu_id;
        $preciomenu->precio = $request->precio;
        $preciomenu->preciopm = $request->preciopm;
        $preciomenu->cantmin = $request->cantmin;
        $preciomenu->save();
       

        return redirect()->route('precios.index')
            ->with('success', 'Precio editado correctamente.');
    }

    public function destroy($id)
    {
        $preciomenu = Preciomenu::find($id)->delete();

        return redirect()->route('precios.index')
            ->with('success', 'Precio eliminado correctamente.');
    }
}
