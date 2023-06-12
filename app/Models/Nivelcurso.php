<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Nivelcurso
 *
 * @property $id
 * @property $nombre
 * @property $sucursale_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Curso[] $cursos
 * @property Preciomenu[] $preciomenuses
 * @property Sucursale $sucursale
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Nivelcurso extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'sucursale_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','sucursale_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos()
    {
        return $this->hasMany('App\Models\Curso', 'nivelcurso_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preciomenuses()
    {
        return $this->hasMany('App\Models\Preciomenu', 'nivelcurso_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
    

}
