<?php

namespace Database\Seeders;

use App\Models\Catitem;
use App\Models\Estadopago;
use App\Models\Tipopago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        Catitem::create([
            "nombre" => "BOLLERIA",
        ]);
        Catitem::create([
            "nombre" => "BEBIDA",
        ]);
        Catitem::create([
            "nombre" => "FRUTA",
        ]);
        Catitem::create([
            "nombre" => "SOPA",
        ]);
        Catitem::create([
            "nombre" => "SEGUNDO",
        ]);
        Catitem::create([
            "nombre" => "ENSALADA",
        ]);
        Catitem::create([
            "nombre" => "GUARNICION",
        ]);
        Catitem::create([
            "nombre" => "POSTRE",
        ]);
        Catitem::create([
            "nombre" => "REFRESCO",
        ]);

        

        Estadopago::create([
            "nombre" => "POR PAGAR",
            "abreviatura" => "PP",
        ]);
        Estadopago::create([
            "nombre" => "PAGO REALIZADO",
            "abreviatura" => "PR",
        ]);
        Estadopago::create([
            "nombre" => "PAGO ANULADO",
            "abreviatura" => "PA",
        ]);

        \App\Models\Moneda::create([
            'nombre'=>'Boliviano',
            'Abreviatura'=>'Bs.',
            'tasacambio'=>'6.96',
            'predeterminado'=>'1',
        ]);

        

        \App\Models\Tutore::create([
            'nombre'=>'PREDETERMINADO',
            'cedula'=>'0',
            'correo'=>'S/C',
            'telefono'=>'0'
        ]);
    }
}
