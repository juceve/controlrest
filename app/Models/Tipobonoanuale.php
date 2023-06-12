<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipobonoanuale
 *
 * @property $id
 * @property $tipomenu_id
 * @property $precio
 * @property $sucursale_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Sucursale $sucursale
 * @property Tipomenu $tipomenu
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tipobonoanuale extends Model
{
    
    static $rules = [
		'tipomenu_id' => 'required',
		'precio' => 'required',
		'sucursale_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['tipomenu_id','precio','sucursale_id'];


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
    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }
    

}
