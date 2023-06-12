<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preciomenu extends Model
{
    
    static $rules = [
		'nivelcurso_id' => 'required',
		'tipomenu_id' => 'required',
		'precio' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nivelcurso_id','tipomenu_id','precio', 'preciopm', 'cantmin','sucursale_id'];


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
    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }
    
    public function sucursale()
    {
        return $this->hasOne('App\Models\Sucursale', 'id', 'sucursale_id');
    }
}
