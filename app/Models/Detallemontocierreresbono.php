<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallemontocierreresbono
 *
 * @property $id
 * @property $cierrereservabonos_id
 * @property $tipopago_id
 * @property $tipopago
 * @property $cantidad
 * @property $importe
 * @property $created_at
 * @property $updated_at
 *
 * @property Cierrereservabono $cierrereservabono
 * @property Tipopago $tipopago
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallemontocierreresbono extends Model
{
    
    static $rules = [
		'cierrereservabono_id' => 'required',
		'tipopago_id' => 'required',
		'tipopago' => 'required',
		'cantidad' => 'required',
		'importe' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cierrereservabono_id','tipopago_id','tipopago','cantidad','importe'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cierrereservabono()
    {
        return $this->hasOne('App\Models\Cierrereservabono', 'id', 'cierrereservabono_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipopago()
    {
        return $this->hasOne('App\Models\Tipopago', 'id', 'tipopago_id');
    }
    

}
