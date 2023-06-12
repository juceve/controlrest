<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $catproducto_id
 * @property $imagen
 * @property $stock
 * @property $precio
 * @property $sucursale_id
 * @property $visible
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Catproducto $catproducto
 * @property Sucursale $sucursale
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Producto extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'descripcion' => 'required',
		'catproducto_id' => 'required',
		'visible' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','descripcion','catproducto_id','imagen','stock','precio','sucursale_id','visible','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function catproducto()
    {
        return $this->hasOne('App\Models\Catproducto', 'id', 'catproducto_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    

}
