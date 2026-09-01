<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';
    protected $guarded = [];

    protected $casts = [
        'monthly_price' => 'float',
        'annual_price' => 'float',
        'lifetime_price' => 'float',
        'settings' => 'array',
        'is_enabled' => 'boolean',
        'order' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'plan_id', 'slug');
    }
}
