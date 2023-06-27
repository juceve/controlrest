<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'verificado' => 'required',
		'esestudiante' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo','nombre','cedula','correo','telefono','tutore_id','curso_id','verificado','esestudiante'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonoanuales()
    {
        return $this->hasMany('App\Models\Bonoanuale', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonofechas()
    {
        return $this->hasMany('App\Models\Bonofecha', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditoprofesores()
    {
        return $this->hasMany('App\Models\Creditoprofesore', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function curso()
    {
        return $this->hasOne('App\Models\Curso', 'id', 'curso_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entregalounches()
    {
        return $this->hasMany('App\Models\Entregalounch', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licencias()
    {
        return $this->hasMany('App\Models\Licencia', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loncheras()
    {
        return $this->hasMany('App\Models\Lonchera', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pagos()
    {
        return $this->hasMany('App\Models\Pago', 'estudiante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tutore()
    {
        return $this->hasOne('App\Models\Tutore', 'id', 'tutore_id');
    }
    

}
