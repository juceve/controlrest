<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Curso
 *
 * @property $id
 * @property $nombre
 * @property $nivelcurso_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Estudiante[] $estudiantes
 * @property Nivelcurso $nivelcurso
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Curso extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'nivelcurso_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','nivelcurso_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estudiantes()
    {
        return $this->hasMany('App\Models\Estudiante', 'curso_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nivelcurso()
    {
        return $this->hasOne('App\Models\Nivelcurso', 'id', 'nivelcurso_id');
    }    
    
    // public function sucursale()
    // {
    //     return $this->hasOne(Sucursale::class, 'id', 'sucursale_id');
    // }

}
