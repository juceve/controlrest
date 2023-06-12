<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Estudiante;
use App\Models\Menu;
use App\Models\Tipomenu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Nueva extends Component
{
    public $estudiantes = null, $nombreCliente = "", $cliente = null, $busqueda = "", $sucursale_id="";
    public $menus=null, $selProductos, $extras=null;

    public function mount($id){
        $this->sucursale_id = Auth::user()->sucursale_id;
        if($id != '0'){
            $this->seleccionarEstudiante($id);
        }    
        $fecActual = date('Y-m-d');
        $sql = "SELECT e.id evento_id, e.fecha, e.semana,m.id menu_id,m.nombre menu, tm.nombre tipomenu FROM eventos e
        INNER JOIN detalleeventos de on de.evento_id = e.id
        INNER JOIN menus m on m.id = de.menu_id
        INNER JOIN tipomenus tm on tm.id = m.tipomenu_id
        WHERE e.sucursale_id = $this->sucursale_id
        AND e.fecha >= '$fecActual'
        ORDER BY e.fecha";
        $this->menus = DB::select($sql);
        
        $tipos = Tipomenu::where('nombre','EXTRA')->first();
        $this->extras = $tipos->menuses()->get();
        
    }

    public function updatedBusqueda()
    {
        if ($this->busqueda == "") {
            $this->reset(['estudiantes']);
        } else {
            $sucursale_id = Auth::user()->sucursale_id;
            $sql = "SELECT e.*, c.nombre curso, n.nombre nivel FROM estudiantes e
        INNER JOIN cursos c on c.id = e.curso_id
        INNER JOIN nivelcursos n on n.id = c.nivelcurso_id
        WHERE n.id = $sucursale_id AND e.codigo LIKE '%" . $this->busqueda . "%' OR e.nombre LIKE '%" . $this->busqueda . "%' OR e.cedula LIKE '%" . $this->busqueda . "%' OR c.nombre LIKE '%" . $this->busqueda . "%'";
            $this->estudiantes = DB::select($sql);
        }
    }
    public function render()
    {
        return view('livewire.ventas.nueva')->extends('layouts.app');
    }

    public function seleccionarEstudiante($id)
    {
        $this->cliente = Estudiante::find($id);
        $this->nombreCliente = $this->cliente->nombre . ' - ' . $this->cliente->curso->nombre . ' - ' . $this->cliente->curso->nivelcurso->nombre;
        $this->reset(['estudiantes']);
    }

    public function resetAll()
    {
        $this->reset(['busqueda', 'estudiantes', 'nombreCliente']);
    }
}
