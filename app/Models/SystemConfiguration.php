<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    protected $fillable = [
        'hospital_name',
        'program_name',
        'program_manager',
        'app_logo',
        'hospital_logo',
        'first_delivery_days',
        'subsequent_delivery_days'
    ];

    protected $casts = [
        'first_delivery_days' => 'integer',
        'subsequent_delivery_days' => 'integer'
    ];

    public static function getConfig()
    {
        return cache()->remember('system_config', 3600, function () {
            return self::first() ?? self::create([]);
        });
    }

    public static function clearCache()
    {
        cache()->forget('system_config');
    }
}