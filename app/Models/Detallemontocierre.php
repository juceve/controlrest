<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallemontocierre
 *
 * @property $id
 * @property $cierrecaja_id
 * @property $tipopago_id
 * @property $tipopago
 * @property $cantidad
 * @property $importe
 * @property $created_at
 * @property $updated_at
 *
 * @property Cierrecaja $cierrecaja
 * @property Tipopago $tipopago
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallemontocierre extends Model
{
    
    static $rules = [
		'cierrecaja_id' => 'required',
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
    protected $fillable = ['cierrecaja_id','tipopago_id','tipopago','cantidad','importe'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cierrecaja()
    {
        return $this->hasOne('App\Models\Cierrecaja', 'id', 'cierrecaja_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipopago()
    {
        return $this->hasOne('App\Models\Tipopago', 'id', 'tipopago_id');
    }
    

}
