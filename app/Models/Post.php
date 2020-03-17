<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'description',
        'published',
        'user_id'
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}