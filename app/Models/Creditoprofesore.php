<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Creditoprofesore
 *
 * @property $id
 * @property $estudiante_id
 * @property $pago_id
 * @property $pagado
 * @property $created_at
 * @property $updated_at
 *
 * @property Estudiante $estudiante
 * @property Pago $pago
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Creditoprofesore extends Model
{
    
    static $rules = [
		'estudiante_id' => 'required',
		'venta_id' => 'required',
		'pagado' => 'required',
        'sucursale_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['estudiante_id','venta_id','pagado','sucursale_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estudiante()
    {
        return $this->hasOne('App\Models\Estudiante', 'id', 'estudiante_id');
    }

    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function venta()
    {
        return $this->hasOne('App\Models\Venta', 'id', 'venta_id');
    }
    

}
