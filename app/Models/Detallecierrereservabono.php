<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallecierrereservabono
 *
 * @property $id
 * @property $cierrereservabono_id
 * @property $descripcion
 * @property $tipopago
 * @property $descuento
 * @property $cantidad
 * @property $preciounitario
 * @property $importe
 * @property $created_at
 * @property $updated_at
 *
 * @property Cierrereservabono $cierrereservabono
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallecierrereservabono extends Model
{
    
    static $rules = [
		'cierrereservabono_id' => 'required',
		'descripcion' => 'required',
		'tipopago' => 'required',
		'descuento' => 'required',
		'cantidad' => 'required',		
		'importe' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cierrereservabono_id','descripcion','tipopago','descuento','cantidad','importe'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cierrereservabono()
    {
        return $this->hasOne('App\Models\Cierrereservabono', 'id', 'cierrereservabono_id');
    }
    

}
