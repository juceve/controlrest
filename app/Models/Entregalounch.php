<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entregalounch extends Model
{
    
    static $rules = [
		'fechaentrega' => 'required',
		'user_id' => 'required',
		'sucursale_id' => 'required',
    ];

    protected $perPage = 20;


    protected $fillable = ['fechaentrega','detallelonchera_id','menu_id', 'producto_id', 'venta_id','user_id','sucursale_id','estado','estudiante_id','observaciones'];


    public function detallelonchera()
    {
        return $this->hasOne('App\Models\Detallelonchera', 'id', 'detallelonchera_id');
    }
    

    public function menu()
    {
        return $this->hasOne('App\Models\Menu', 'id', 'menu_id');
    }
    

    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function producto()
    {
        return $this->hasOne('App\Models\Producto', 'id', 'producto_id');
    }

    public function estudiante()
    {
        return $this->hasOne('App\Models\Estudiante', 'id', 'estudiante_id');
    }
  
    public function venta()
    {
        return $this->hasOne('App\Models\Venta', 'id', 'venta_id');
    }
    

}
