<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallecierrereservabono
 *
 * @property $id
 * @property $descripcion
 * @property $tipopago
 * @property $descuento
 * @property $cantidad
 * @property $preciounitario
 * @property $importe
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallecierrereservabono extends Model
{
    
    static $rules = [
		'descripcion' => 'required',
		'tipopago' => 'required',
		'descuento' => 'required',
		'cantidad' => 'required',
		'preciounitario' => 'required',
		'importe' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['descripcion','tipopago','descuento','cantidad','preciounitario','importe'];



}
