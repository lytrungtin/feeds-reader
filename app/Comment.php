<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comments'
    ];

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
