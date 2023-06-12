<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    
    static $rules = [
		'nombre' => 'required|unique:items',
		'descripcion' => 'required',
		'catitem_id' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','descripcion','catitem_id','imagen','stock','precio', 'sucursale_id','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function catitem()
    {
        return $this->hasOne('App\Models\Catitem', 'id', 'catitem_id');
    }
    
    public function Sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleloncheras()
    {
        return $this->hasMany('App\Models\Detallelonchera', 'item_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallemenuses()
    {
        return $this->hasMany('App\Models\Detallemenu', 'item_id', 'id');
    }
    

}
