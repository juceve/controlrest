<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logsid
 *
 * @property $id
 * @property $idgenerado
 * @property $tabla
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Logsid extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['idgenerado','tabla','observaciones'];



}
