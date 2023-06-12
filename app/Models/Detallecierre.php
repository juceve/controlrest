<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallecierre
 *
 * @property $id
 * @property $cierrecaja_id
 * @property $descripcion
 * @property $tipopago
 * @property $descuento
 * @property $cantidad
 * @property $preciounitario
 * @property $importe
 * @property $created_at
 * @property $updated_at
 *
 * @property Cierrecaja $cierrecaja
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallecierre extends Model
{
    
    static $rules = [
		'cierrecaja_id' => 'required',
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
    protected $fillable = ['cierrecaja_id','descripcion','tipopago','descuento','cantidad','preciounitario','importe'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cierrecaja()
    {
        return $this->hasOne('App\Models\Cierrecaja', 'id', 'cierrecaja_id');
    }
    

}
