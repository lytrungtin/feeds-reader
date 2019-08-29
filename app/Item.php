<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    private $id, $title, $publish_date, $description, $updated_by, $created_by;

    protected $fillable = [
        'title', 'description', 'publish_date'
    ];

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function editor()
    {
        return $this->belongsTo('App\User','updated_by');
    }

    public function link()
    {
        return $this->belongsTo('App\Link');
    }

    public function comments()
    {
        return $this->belongsTo('App\Comment');
    }

    public function scopeCategoryID($query, $category_id)
    {
        if (!is_null($category_id) && (int)$category_id > 0) {
            return $query->where('category_id', $category_id);
        }

        return $query;
    }
}
