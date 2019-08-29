<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Generator extends Model
{
    protected $fillable = [
        'generator'
    ];

    public function channels()
    {
        return $this->hasMany('App\Channel');
    }
}
