<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lonchera extends Model
{
    
    static $rules = [
		'fecha' => 'required',
		'estudiante_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fecha','estudiante_id','venta_id','habilitado', 'sucursale_id', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleloncheras()
    {
        return $this->hasMany('App\Models\Detallelonchera', 'lonchera_id', 'id');
    }
    
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
    

}
