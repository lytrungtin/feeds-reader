<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docs extends Model
{
    protected $fillable = [
        'docs',
    ];

    public function channels()
    {
        return $this->hasMany('App\Channel');
    }
}
