<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiolinkBlock extends Model
{
    protected $guarded = [];

    protected $casts = [
        'settings' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
