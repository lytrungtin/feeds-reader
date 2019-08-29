<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'email'
    ];

    public function managing_channels()
    {
        return $this->hasMany('App\Channel','managing_editor_id');
    }

    public function web_master_channels()
    {
        return $this->hasMany('App\Channel','web_master_id');
    }
}
