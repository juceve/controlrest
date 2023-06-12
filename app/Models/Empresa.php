<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 *
 * @property $id
 * @property $razonsocial
 * @property $direccion
 * @property $telefono
 * @property $email
 * @property $nit
 * @property $avatar
 * @property $responsable
 * @property $telefono_responsable
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Empresa extends Model
{
    
    static $rules = [
		'razonsocial' => 'required',
		'direccion' => 'required',
		'telefono' => 'required',
		'email' => 'required',
		'nit' => 'required',
		'responsable' => 'required',
		'telefono_responsable' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['razonsocial','direccion','telefono','email','nit','avatar','responsable','telefono_responsable','estado'];



}
