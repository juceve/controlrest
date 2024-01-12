<?php

namespace App\Http\Livewire;

use App\Models\Curso;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Promociones extends Component
{
    public $selCurso = "", $selCursoPromo = "", $curso = "", $estudiantes = "", $selEstudiantes = [];

    public function render()
    {
        $cursos = DB::table('cursos')
            ->join('nivelcursos', 'nivelcursos.id', '=', 'cursos.nivelcurso_id')
            ->where('nivelcursos.sucursale_id', Auth::user()->sucursale_id)
            ->select('cursos.id', 'cursos.nombre as curso', 'nivelcursos.nombre as nivel')
            ->orderBy('cursos.nombre', 'ASC')
            ->get();
        return view('livewire.promociones', compact('cursos'))->extends('layouts.app');
    }

    protected $listeners = ['promover','desafiliar'];

    public function deseleccionar()
    {
        $this->reset('selEstudiantes');
    }

    public function selTodo()
    {
        $this->reset('selEstudiantes');
        foreach ($this->estudiantes as $estudiante) {
            $this->selEstudiantes[] = $estudiante->id;
        }
    }

    public function updatedSelCurso()
    {
        $this->curso = Curso::find($this->selCurso);
        $this->estudiantes = $this->curso->estudiantes->where('verificado', 1);
        foreach ($this->estudiantes as $estudiante) {
            $this->selEstudiantes[] = $estudiante->id;
        }
    }

    public function promover()
    {
        DB::beginTransaction();
        try {
            foreach ($this->selEstudiantes as $estudiante_id) {
                $est = Estudiante::find($estudiante_id);
                $est->curso_id = $this->selCursoPromo;
                $est->save();
            }
            DB::commit();
            $this->reset('selCurso', 'selCursoPromo', 'curso', 'estudiantes', 'selEstudiantes');
            $this->emit('success','Estudiantes promovidos correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error','Ha ocurrido un error');
        }
    }

    public function desafiliar()
    {
        DB::beginTransaction();
        try {
            foreach ($this->selEstudiantes as $estudiante_id) {
                $est = Estudiante::find($estudiante_id);
                $est->curso_id = NULL;
                $est->verificado = false;
                $est->save();
            }
            DB::commit();
            $this->reset('selCurso', 'selCursoPromo', 'curso', 'estudiantes', 'selEstudiantes');
            $this->emit('success','Estudiantes desafiliados correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error','Ha ocurrido un error');
        }
    }
}
