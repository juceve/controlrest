<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursale extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'direccion' => 'required',
		'telefono' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

 
    protected $fillable = ['nombre','direccion','telefono','estado','horalimitepedidos'];

    public function cierrecajas()
    {
        return $this->hasMany('App\Models\Cierrecaja', 'sucursale_id', 'id');
    }

    public function entregalounches()
    {
        return $this->hasMany('App\Models\Entregalounch', 'sucursale_id', 'id');
    }

    public function eventos()
    {
        return $this->hasMany('App\Models\Evento', 'sucursale_id', 'id');
    }
    

    public function feriados()
    {
        return $this->hasMany('App\Models\Feriado', 'sucursale_id', 'id');
    }
    

    public function items()
    {
        return $this->hasMany('App\Models\Item', 'sucursale_id', 'id');
    }
    
    public function creditoprofesore()
    {
        return $this->hasMany('App\Models\Creditoprofesore', 'sucursale_id', 'id');
    }

    public function menuses()
    {
        return $this->hasMany('App\Models\Menu', 'sucursale_id', 'id');
    }
    

    public function nivelcursos()
    {
        return $this->hasMany('App\Models\Nivelcurso', 'sucursale_id', 'id');
    }
    

    public function preciomenuses()
    {
        return $this->hasMany('App\Models\Preciomenu', 'sucursale_id', 'id');
    }
    

    public function tipobonoanuales()
    {
        return $this->hasMany('App\Models\Tipobonoanuale', 'sucursale_id', 'id');
    }
    

    public function tipomenuses()
    {
        return $this->hasMany('App\Models\Tipomenu', 'sucursale_id', 'id');
    }
    

    public function users()
    {
        return $this->hasMany('App\Models\User', 'sucursale_id', 'id');
    }
    

    public function ventas()
    {
        return $this->hasMany('App\Models\Venta', 'sucursale_id', 'id');
    }
    
   
    public function ventasconfigs()
    {
        return $this->hasMany('App\Models\Ventasconfig', 'sucursale_id', 'id');
    }
    

}
