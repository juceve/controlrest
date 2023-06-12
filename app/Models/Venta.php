<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    static $rules = [
		'fecha' => 'required',
		'cliente' => 'required',
		'estadopago_id' => 'required',
		'tipopago_id' => 'required',
		'importe' => 'required',
		'estado' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fecha','cliente','estadopago_id','tipopago_id','importe','sucursale_id','plataforma','observaciones','estado','user_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleventas()
    {
        return $this->hasMany('App\Models\Detalleventa', 'venta_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entregalounches()
    {
        return $this->hasMany('App\Models\Entregalounch', 'venta_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estadopago()
    {
        return $this->hasOne('App\Models\Estadopago', 'id', 'estadopago_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pagos()
    {
        return $this->hasMany('App\Models\Pago', 'venta_id', 'id');
    }
    
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
    public function tipopago()
    {
        return $this->hasOne('App\Models\Tipopago', 'id', 'tipopago_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function loncheras()
    {
        return $this->hasMany('App\Models\Lonchera', 'venta_id', 'id');
    }

}
