<?php

namespace App\Http\Livewire;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Tutore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Masivos extends Component
{
    use WithFileUploads;

    public $cursos = null, $selCurso = "", $tutores, $selTutor = "", $file;

    public function mount()
    {
        $this->cursos = Curso::all();
        $this->tutores = Tutore::all();
    }
    public function render()
    {
        return view('livewire.masivos')->extends('layouts.app');
    }

    public function ejecutar()
    {
        DB::beginTransaction();
        try {
            if ($this->file && $this->selCurso != "") {
                if ($this->file) {
                    $extension =  $this->file->clientExtension();
                    $path = $this->file->storeAs(
                        '/txt',
                        $this->selCurso . ".txt"
                    );
                    $imagen = $path;
                }
            }

            $rutaArchivo = Storage::url('txt/' . $this->selCurso . '.txt');

            // Leer el contenido del archivo
            $contenidoArchivo = file_get_contents($rutaArchivo);

            // Dividir el contenido por saltos de línea
            $arrEstudiantes = explode("\n", $contenidoArchivo);
            foreach ($arrEstudiantes as $item) {
                $data = explode(",", $item);
                $estudiante = Estudiante::create([
                    "nombre" => utf8_encode($data[0]),
                    "cedula" => utf8_encode($data[1]),
                    "telefono" => utf8_encode($data[2]),
                    // "nombre" => $item,
                    "tutore_id" => $this->selTutor,
                    "curso_id" => $this->selCurso
                ]);
                $codigo = utf8_encode($this->obtenerIniciales($item)) . $estudiante->id;
                $estudiante->codigo = str_pad($codigo, 10, "0", STR_PAD_LEFT);
                $estudiante->save();
            }

            DB::commit();
            $this->reset(['selCurso', 'selTutor', 'file']);
            $this->emit('success', 'Se proceso la ejecutacion');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->reset(['selCurso', 'selTutor', 'file']);
            $this->emit('error', $th->getMessage());
        }
    }

    public function obtenerIniciales($nombre)
    {
        $iniciales = ''; // Variable para almacenar las iniciales

        // Dividir el nombre en palabras
        $palabras = explode(' ', utf8_encode($nombre));

        // Iterar sobre cada palabra y obtener la primera letra
        foreach ($palabras as $palabra) {
            $inicial = substr($palabra, 0, 1); // Obtener la primera letra de la palabra
            $iniciales .= $inicial; // Agregar la inicial a la cadena de iniciales
        }

        return substr($iniciales, 0, 2);
    }
}
