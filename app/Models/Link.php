<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'settings' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function biolinkBlocks()
    {
        return $this->hasMany(BiolinkBlock::class)->orderBy('order');
    }

    public function getFullUrlAttribute()
    {
        if ($this->domain_id > 0 && $this->domain) {
            $scheme = $this->domain->scheme ?: 'https://';
            return rtrim($scheme . $this->domain->host, '/') . '/' . ltrim($this->url, '/');
        }
        return url($this->url);
    }
}
