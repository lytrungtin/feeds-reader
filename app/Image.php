<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'url', 'title', 'description', 'width', 'height'
    ];

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function link()
    {
        return $this->belongsTo('App\Link');
    }
}
