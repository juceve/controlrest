<?php

namespace Database\Seeders;

use App\Models\Catproducto;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Tipobonoanuale;
use App\Models\Tipopago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sucursale = Sucursale::create([
            'nombre' => 'SANTO TOMAS DE AQUINO',
            'direccion' => '1',
            'telefono' => '1',
            'estado' => true,
            'horalimitepedidos' => '09:30',
        ]);
        $categoria = Catproducto::create(['nombre'=>'ventas']);  

        Producto::create([
            'nombre' => 'BONO ANUAL',
            'descripcion' => 'Bono de Alimentos por gestion completa',
            'catproducto_id' => $categoria->id,
            'sucursale_id' => $sucursale->id,
            'visible' => true,
            'estado' => true,
        ]);

        Producto::create([
            'nombre' => 'BONO POR FECHA',
            'descripcion' => 'Bono de Alimentos por rango de fechas',
            'catproducto_id' => $categoria->id,
            'sucursale_id' => $sucursale->id,
            'visible' => true,
            'estado' => true,
        ]);

        Producto::create([
            'nombre' => 'COMPRAS Y RESERVAS',
            'descripcion' => 'Loncheras de reserva de alimentos',
            'catproducto_id' => $categoria->id,
            'sucursale_id' => $sucursale->id,
            'visible' => true,
            'estado' => true,
        ]);

        Producto::create([
            'nombre' => 'PUNTO DE VENTA',
            'descripcion' => 'Ventas directas',
            'catproducto_id' => $categoria->id,
            'sucursale_id' => $sucursale->id,
            'visible' => true,
            'estado' => true,
        ]);

        Producto::create([
            'nombre' => 'PROFESORES',
            'descripcion' => 'Entregas a credito desde la Plataforma Profesores',
            'catproducto_id' => $categoria->id,
            'sucursale_id' => $sucursale->id,
            'visible' => true,
            'estado' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Julio Veliz',
            'email' => 'julio@correo.com',
            'password' => bcrypt('12345678'),
            'sucursale_id' => $sucursale->id,
        ])->assignRole('Programador');

        \App\Models\User::factory()->create([
            'name' => 'Cristian Castro',
            'email' => 'ccastro@gmail.com',
            'password' => bcrypt('12345678'),
            'sucursale_id' => $sucursale->id,
        ])->assignRole('Programador');

        \App\Models\Tipomenu::create([
            'nombre'=>'ALMUERZO COMPLETO',
            'abr' => 'AC',
            'sucursale_id' => $sucursale->id,
        ]);
        \App\Models\Tipomenu::create([
            'nombre'=>'ALMUERZO SIMPLE',
            'abr' => 'AS',
            'sucursale_id' => $sucursale->id,
        ]);
        \App\Models\Tipomenu::create([
            'nombre'=>'EXTRA',
            'abr' => 'EX',
            'sucursale_id' => $sucursale->id,
        ]);

        Tipobonoanuale::create([
            'tipomenu_id' => 1,
            'precio' => 3600,
            'sucursale_id' => $sucursale->id,
        ]);

        Tipopago::create([
            "nombre" => "EFECTIVO - LOCAL",
            "abreviatura" => "EF",
            "factor" =>1,
        ]);
        Tipopago::create([
            "nombre" => "TRANSFERENCIA BANCARIA",
            "abreviatura" => "TB",
            "factor" =>1,
        ]);
        Tipopago::create([
            "nombre" => "PAGO QR",
            "abreviatura" => "QR",
            "factor" =>1,
        ]);
        Tipopago::create([
            "nombre" => "CREDITO",
            "abreviatura" => "CR",
            "factor" =>1,
        ]);
        Tipopago::create([
            "nombre" => "GASTO ADMINISTRATIVO",
            "abreviatura" => "GA",
            "factor" =>0,
        ]);
    }
}