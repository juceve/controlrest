<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Nivelcurso;
use App\Models\Tutore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Vinculosestudiantes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nombre, $cedula, $correo, $telefono, $curso_id, $tutore_id, $estudiante, $checks = [];
    public $busquedaestudiante = "", $resultados = null, $tutor = null, $esestudiante = true;

    protected $listeners = ['render', 'store', 'edit', 'resetear', 'destroy', 'desvincular'];
    protected $rules = [
        'nombre' => 'required',
        'curso_id' => 'required',
    ];

    public function mount($id)
    {
        $this->tutore_id = $id;
    }

    // public function updatedBusquedaestudiante()
    // {
    //     if ($this->busquedaestudiante != "") {
    //         $sql = "SELECT e.id, e.codigo,e.nombre,e.cedula,c.nombre curso, n.nombre nivel
    //     FROM estudiantes e
    //     INNER JOIN cursos c on c.id = e.curso_id
    //     INNER JOIN nivelcursos n on n.id = c.nivelcurso_id
    //     WHERE e.tutore_id = 0       
    //     AND e.codigo LIKE '%" . $this->busquedaestudiante . "%'
    //     OR e.tutore_id = 0  AND e.nombre LIKE '%" . $this->busquedaestudiante . "%'
    //     OR e.tutore_id = 0 AND e.cedula LIKE '%" . $this->busquedaestudiante . "%'
    //     OR e.tutore_id = 0  AND c.nombre LIKE '%" . $this->busquedaestudiante . "%'";

    //         $this->resultados = DB::select($sql);
    //     } else {
    //         $this->reset(['busquedaestudiante', 'resultados']);
    //     }
    // }
    public function render()
    {
        if ($this->busquedaestudiante != "") {
            $sql = "SELECT e.id, e.codigo,e.nombre,e.cedula,c.nombre curso, n.nombre nivel
        FROM estudiantes e
        INNER JOIN cursos c on c.id = e.curso_id
        INNER JOIN nivelcursos n on n.id = c.nivelcurso_id
        WHERE e.tutore_id = 0       
        AND e.codigo LIKE '%" . $this->busquedaestudiante . "%'
        OR e.tutore_id = 0  AND e.nombre LIKE '%" . $this->busquedaestudiante . "%'
        OR e.tutore_id = 0 AND e.cedula LIKE '%" . $this->busquedaestudiante . "%'
        OR e.tutore_id = 0  AND c.nombre LIKE '%" . $this->busquedaestudiante . "%'";

            $this->resultados = DB::select($sql);
        } else {
            $this->reset(['busquedaestudiante', 'resultados']);
        }
        $estudiantes = Estudiante::where('tutore_id', $this->tutore_id)
            ->get();

        $tutor = Tutore::find($this->tutore_id);
        $this->tutor = $tutor;
        $cursos = "";
        if (Auth::user()->sucursale_id != "") {
            $niveles = Nivelcurso::where('sucursale_id', Auth::user()->sucursale_id)->get();
            $cursos = array();
            $i = 0;
            foreach ($niveles as $items) {
                $item = $items->cursos;
                foreach ($item as $curso) {
                    $cursos[$i] = $curso;
                    $i++;
                }
            }
        } else {
            $cursos = Curso::all()->pluck('nombre', 'id');
        }
        $this->emit('datatableRender');

        return view('livewire.clientes.vinculosestudiantes', compact('tutor', 'estudiantes', 'cursos'))->extends('layouts.app');
    }

    public function store()
    {

        $estudiante = new Estudiante();
        $estudiante->nombre = $this->nombre;
        $estudiante->cedula = $this->cedula;
        $estudiante->correo = $this->correo;
        $estudiante->telefono = $this->telefono;
        $estudiante->curso_id = $this->curso_id;
        $estudiante->esestudiante = $this->esestudiante;
        $estudiante->tutore_id = $this->tutore_id;
        $this->validate();
        DB::beginTransaction();
        try {
            $estudiante->save();
            $codigo = $this->obtenerIniciales($estudiante->nombre) . $estudiante->id;
            $estudiante->codigo = str_pad($codigo, 10, "0", STR_PAD_LEFT);
            $estudiante->save();
            DB::commit();
            $this->reset('nombre', 'cedula', 'telefono', 'curso_id', 'estudiante', 'correo', 'esestudiante');
            $this->emit('success', 'Estudiante creado correctamente');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('error', 'Ha ocurrido un error, no se registro la transacción.');
        }
    }

    public function obtenerIniciales($nombre)
    {
        $iniciales = ''; // Variable para almacenar las iniciales

        // Dividir el nombre en palabras
        $palabras = explode(' ', $nombre);

        // Iterar sobre cada palabra y obtener la primera letra
        foreach ($palabras as $palabra) {
            $inicial = substr($palabra, 0, 1); // Obtener la primera letra de la palabra
            $iniciales .= $inicial; // Agregar la inicial a la cadena de iniciales
        }

        return substr($iniciales, 0, 2);
    }

    public function edit($id)
    {
        $this->estudiante = Estudiante::find($id);
        $this->nombre = $this->estudiante->nombre;
        $this->cedula = $this->estudiante->cedula;
        $this->correo = $this->estudiante->correo;
        $this->telefono = $this->estudiante->telefono;
        $this->curso_id = $this->estudiante->curso_id;
    }

    public function destroy($id)
    {
        try {
            $estudiante = Estudiante::find($id)->delete();
            $this->emit('success', 'Estudiante eliminado correctamente');
        } catch (\Throwable $th) {
            $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function desvincular($id)
    {
        try {
            $estudiante = Estudiante::find($id);
            $estudiante->tutore_id = 0;
            $estudiante->save();
            $this->emit('success', 'Estudiante desvinculado correctamente');
        } catch (\Throwable $th) {
            $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function resetear()
    {
        $this->reset('nombre', 'cedula', 'telefono', 'curso_id', 'estudiante', 'correo');
    }

    public function update()
    {
        $this->estudiante->nombre = $this->nombre;
        $this->estudiante->cedula = $this->cedula;
        $this->estudiante->correo = $this->correo;
        $this->estudiante->telefono = $this->telefono;
        $this->estudiante->curso_id = $this->curso_id;
        $this->validate();
        DB::beginTransaction();
        try {
            $this->estudiante->save();
            DB::commit();
            $this->reset('nombre', 'cedula', 'telefono', 'estudiante', 'curso_id', 'correo');
            $this->emit('success', 'Estudiante editado correctamente');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('error', 'Ha ocurrido un error, no se registró la transacción.');
        }
    }

    public function asignar()
    {

        try {
            if ($this->checks) {
                DB::beginTransaction();
                foreach ($this->checks as $id) {
                    $estudiante = Estudiante::find($id);
                    $estudiante->tutore_id = $this->tutor->id;
                    $estudiante->save();
                }
                DB::commit();
                $this->reset(['busquedaestudiante', 'resultados']);
                $this->emit('success', 'Estudiante(s) asignado(s) correctamente.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error.');
        }
    }
}
