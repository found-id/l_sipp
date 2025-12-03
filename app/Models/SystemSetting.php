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

    /**
     * Get font configuration
     */
    public static function getFontConfig()
    {
        $font = self::getValue('system_font', 'default');
        
        $fonts = [
            'default' => [
                'name' => 'Default (System UI)',
                'url' => null,
                'family' => "ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
            ],
            'poppins' => [
                'name' => 'Poppins',
                'url' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
                'family' => "'Poppins', sans-serif"
            ],
            'inter' => [
                'name' => 'Inter',
                'url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
                'family' => "'Inter', sans-serif"
            ],
            'ibm_plex_sans' => [
                'name' => 'IBM Plex Sans',
                'url' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap',
                'family' => "'IBM Plex Sans', sans-serif"
            ],
            'archivo' => [
                'name' => 'Archivo',
                'url' => 'https://fonts.googleapis.com/css2?family=Archivo:wght@300;400;500;600;700&display=swap',
                'family' => "'Archivo', sans-serif"
            ],
            'space_grotesk' => [
                'name' => 'Space Grotesk',
                'url' => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap',
                'family' => "'Space Grotesk', sans-serif"
            ],
            'bricolage_grotesque' => [
                'name' => 'Bricolage Grotesque',
                'url' => 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@300;400;500;600;700&display=swap',
                'family' => "'Bricolage Grotesque', sans-serif"
            ],
        ];

        return $fonts[$font] ?? $fonts['default'];
    }
}