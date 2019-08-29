<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'link',
    ];

    public function channels()
    {
        return $this->hasMany('App\Channel');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }
}
