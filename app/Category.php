<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'content',
    ];

    public function domain()
    {
        return $this->belongsTo('App\Domain');
    }


    public function channels()
    {
        return $this->hasMany('App\Channel');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
