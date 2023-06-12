<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bonoanuale
 *
 * @property $id
 * @property $gestion
 * @property $estudiante_id
 * @property $tipomenu_id
 * @property $pago_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Estudiante $estudiante
 * @property Pago $pago
 * @property Tipomenu $tipomenu
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Bonoanuale extends Model
{
    
    static $rules = [
		'gestion' => 'required',
		'estudiante_id' => 'required',
		'tipomenu_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['gestion','estudiante_id','tipomenu_id','venta_id','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estudiante()
    {
        return $this->hasOne('App\Models\Estudiante', 'id', 'estudiante_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function venta()
    {
        return $this->hasOne('App\Models\Venta', 'id', 'venta_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }
    

}
