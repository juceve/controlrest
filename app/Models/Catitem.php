<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Catitem
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Detallemenu[] $detallemenuses
 * @property Item[] $items
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Catitem extends Model
{
    
    static $rules = [
		'nombre' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallemenuses()
    {
        return $this->hasMany('App\Models\Detallemenu', 'catitem_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item', 'catitem_id', 'id');
    }
    

}
