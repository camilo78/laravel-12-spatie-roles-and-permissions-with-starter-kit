<?php

namespace App\Helpers;

use App\Models\SystemConfiguration;

class SystemConfigHelper
{
    public static function get($key = null)
    {
        $config = SystemConfiguration::getConfig();
        
        if ($key) {
            return $config->{$key} ?? null;
        }
        
        return $config;
    }
    
    public static function hospitalName()
    {
        return self::get('hospital_name');
    }
    
    public static function programName()
    {
        return self::get('program_name');
    }
    
    public static function programManager()
    {
        return self::get('program_manager');
    }
    
    public static function appLogo()
    {
        $logo = self::get('app_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }
    
    public static function hospitalLogo()
    {
        $logo = self::get('hospital_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }
    
    public static function firstDeliveryDays()
    {
        return self::get('first_delivery_days');
    }
    
    public static function subsequentDeliveryDays()
    {
        return self::get('subsequent_delivery_days');
    }
}