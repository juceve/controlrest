<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonofecha extends Model
{
    
    static $rules = [
		'fechainicio' => 'required',
		'fechafin' => 'required',
		'estudiante_id' => 'required',
		'tipomenu_id' => 'required',		
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fechainicio','fechafin','estudiante_id', 'tipomenu_id','venta_id', 'sucursale_id', 'estado'];


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
