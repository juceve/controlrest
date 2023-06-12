<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 *
 * @property $id
 * @property $nombre
 * @property $tipomenu_id
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @property Detalleevento[] $detalleeventos
 * @property Detallemenu[] $detallemenuses
 * @property Tipomenu $tipomenu
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Menu extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'tipomenu_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','tipomenu_id','descripcion','sucursale_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleeventos()
    {
        return $this->hasMany('App\Models\Detalleevento', 'menu_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallemenuses()
    {
        return $this->hasMany('App\Models\Detallemenu', 'menu_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }

    public function Sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    
    public function entregalounches()
    {
        return $this->hasMany('App\Models\Entregalounch', 'menu_id', 'id');
    }
}
