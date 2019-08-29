<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    protected $fillable = [
        'title', 'description', 'last_build_date', 'publish_date'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function image()
    {
        return $this->hasOne('App\Image');
    }

    public function link()
    {
        return $this->belongsTo('App\Link');
    }

    public function docs()
    {
        return $this->belongsTo('App\Docs');
    }

    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    public function copyright()
    {
        return $this->belongsTo('App\Copyright');
    }

    public function generator()
    {
        return $this->belongsTo('App\Generator');
    }

    public function editor()
    {
        return $this->belongsTo('App\Person', 'managing_editor_id');
    }

    public function webmaster()
    {
        return $this->belongsTo('App\Person', 'web_master_id');
    }

    public function addItem($item)
    {
        if ($item instanceof Item) {
            return $this->items()->sync([$item->id]);
        }

        return $this->items()->sync($item->pluck('id')->all());
    }

    public function hasItem($item)
    {
        if ($item instanceof Item) {
            return (boolean) $this->items()
                ->where('id', $item->id)
                ->count();
        }

        return (boolean) $this->items()
            ->whereIn('id', $item->pluck('id'))
            ->count();
    }
}
