<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Detallelonchera extends Model
{
    
    static $rules = [
		'lonchera_id' => 'required',
    ];

    protected $perPage = 20;


    protected $fillable = ['fecha','tipomenu_id','lonchera_id','entregado','estado'];


    public function tipomenu()
    {
        return $this->hasOne('App\Models\Tipomenu', 'id', 'tipomenu_id');
    }

    public function lonchera()
    {
        return $this->hasOne('App\Models\Lonchera', 'id', 'lonchera_id');
    }
    

}
