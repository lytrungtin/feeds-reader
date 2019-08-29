<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code', 'language'
    ];

    public function channels()
    {
        return $this->hasMany('App\Channel');
    }
}
