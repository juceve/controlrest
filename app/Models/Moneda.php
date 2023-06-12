<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Moneda
 *
 * @property $id
 * @property $nombre
 * @property $abreviatura
 * @property $tasacambio
 * @property $predeterminado
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Moneda extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'abreviatura' => 'required',
		'tasacambio' => 'required',
		'predeterminado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','abreviatura','tasacambio','predeterminado'];



}
