<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cartmenu
 *
 * @property $id
 * @property $session
 * @property $menu_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Menu $menu
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cartmenu extends Model
{

  static $rules = [
    'session' => 'required',
    'fecha' => 'required',
    'menu_id' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['session', 'menu_id', 'fecha'];


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function menu()
  {
    return $this->hasOne('App\Models\Menu', 'id', 'menu_id');
  }
}
