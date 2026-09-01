<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function shortLinks()
    {
        return $this->hasMany(Link::class)->where('type', 'link');
    }

    public function biolinks()
    {
        return $this->hasMany(Link::class)->where('type', 'biolink');
    }

    public function waRotators()
    {
        return $this->hasMany(Link::class)->where('type', 'warotator');
    }
}
