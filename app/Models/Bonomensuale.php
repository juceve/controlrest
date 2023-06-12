<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bonomensuale
 *
 * @property $id
 * @property $fechainicio
 * @property $fechafin
 * @property $estudiante_id
 * @property $tipomenu_id
 * @property $pago_id
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Estudiante $estudiante
 * @property Pago $pago
 * @property Tipomenu $tipomenu
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Bonomensuale extends Model
{
    
    static $rules = [
		'fechainicio' => 'required',
		'fechafin' => 'required',
		'estudiante_id' => 'required',
		'tipomenu_id' => 'required',
		'pago_id' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fechainicio','fechafin','estudiante_id','tipomenu_id','pago_id','estado'];


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
    public function pago()
    {
        return $this->hasOne('App\Models\Pago', 'id', 'pago_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }
    

}
