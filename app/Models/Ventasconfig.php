<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ventasconfig
 *
 * @property $id
 * @property $sucursale_id
 * @property $nivelcurso_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Nivelcurso $nivelcurso
 * @property Sucursale $sucursale
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ventasconfig extends Model
{
    
    static $rules = [
		'sucursale_id' => 'required',
		'nivelcurso_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['sucursale_id','nivelcurso_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nivelcurso()
    {
        return $this->hasOne('App\Models\Nivelcurso', 'id', 'nivelcurso_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    

}
