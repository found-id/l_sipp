<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description
            ]
        );
    }

    /**
     * Check if setting is enabled (boolean)
     */
    public static function isEnabled($key)
    {
        return self::getValue($key, '0') === '1';
    }

    /**
     * Enable/Disable setting
     */
    public static function setEnabled($key, $enabled = true, $description = null)
    {
        return self::setValue($key, $enabled ? '1' : '0', $description);
    }
}