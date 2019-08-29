<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{

    protected $fillable = [
        'domain',
    ];

    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
