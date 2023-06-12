<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cierrereservabono
 *
 * @property $id
 * @property $fecha
 * @property $hora
 * @property $user_id
 * @property $sucursale_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Sucursale $sucursale
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cierrereservabono extends Model
{
    
    static $rules = [
		'fecha' => 'required',
		'hora' => 'required',
		'user_id' => 'required',
		'sucursale_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fecha','hora','user_id','sucursale_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    

}
