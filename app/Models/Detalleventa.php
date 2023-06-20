<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalleventa extends Model
{
    
    static $rules = [
		'venta_id' => 'required',
		'descripcion' => 'required',
		'subtotal' => 'required',
    ];

    protected $perPage = 20;


    protected $fillable = ['venta_id','producto_id','tipomenu_id','descripcion','cantidad','preciounitario','subtotal','observacion'];

    public function producto()
    {
        return $this->hasOne('App\Models\Producto', 'id', 'producto_id');
    }

    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }

    public function venta()
    {
        return $this->hasOne('App\Models\Venta', 'id', 'venta_id');
    }

}
