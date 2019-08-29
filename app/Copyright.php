<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copyright extends Model
{
    protected $fillable = [
        'copyright'
    ];

    public function channels()
    {
        return $this->hasMany('App\Channel');
    }
}
