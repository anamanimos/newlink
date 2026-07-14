<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackLink extends Model
{
    use HasFactory;

    protected $table = 'track_links';
    public $timestamps = false; // We use datetime
    
    protected $guarded = [];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
    }
}
