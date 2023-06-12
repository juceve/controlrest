<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilitario extends Model
{
    use HasFactory;

    public function contarDiasSemana($fechaInicio, $fechaFin) {
        $diasSemana = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes');
        $contador = 0;
        $fechaActual = new DateTime($fechaInicio);
    
        while ($fechaActual <= new DateTime($fechaFin)) {
            $diaSemana = $fechaActual->format('N');
            if ($diaSemana >= 1 && $diaSemana <= 5) {
                $contador++;
            }
            $fechaActual->modify('+1 day');
        }
    
        return $contador;
    }
}
