<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipopago
 *
 * @property $id
 * @property $nombre
 * @property $abreviatura
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tipopago extends Model
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
    protected $fillable = ['nombre','abreviatura','factor'];



}
