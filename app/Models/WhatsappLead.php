<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLead extends Model
{
    protected $guarded = [];

    public function block()
    {
        return $this->belongsTo(BiolinkBlock::class, 'biolink_block_id');
    }
}
