<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Estadopago
 *
 * @property $id
 * @property $nombre
 * @property $abreviatura
 * @property $created_at
 * @property $updated_at
 *
 * @property Venta[] $ventas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Estadopago extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'abreviatura' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','abreviatura'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ventas()
    {
        return $this->hasMany('App\Models\Venta', 'estadopago_id', 'id');
    }
    

}
