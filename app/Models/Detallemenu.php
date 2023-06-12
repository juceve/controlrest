<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallemenu
 *
 * @property $id
 * @property $menu_id
 * @property $catitem_id
 * @property $item_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Catitem $catitem
 * @property Item $item
 * @property Menu $menu
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallemenu extends Model
{
    
    static $rules = [
		'menu_id' => 'required',
		'catitem_id' => 'required',
		'item_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_id','catitem_id','item_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function catitem()
    {
        return $this->hasOne('App\Models\Catitem', 'id', 'catitem_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function item()
    {
        return $this->hasOne('App\Models\Item', 'id', 'item_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function menu()
    {
        return $this->hasOne('App\Models\Menu', 'id', 'menu_id');
    }
    

}
