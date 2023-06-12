<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feriado
 *
 * @property $id
 * @property $fecha
 * @property $motivo
 * @property $sucursale_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Sucursale $sucursale
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Feriado extends Model
{
    
    static $rules = [
		'fecha' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fecha','motivo','sucursale_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    

}
