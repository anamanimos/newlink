<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting || $setting->value === null) {
            return $default;
        }

        $decoded = json_decode($setting->value, true);
        return (json_last_error() === JSON_ERROR_NONE && !is_numeric($setting->value)) ? $decoded : $setting->value;
    }

    public static function set($key, $value)
    {
        $encoded = is_array($value) || is_object($value) ? json_encode($value) : $value;
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $encoded]
        );
    }
}
