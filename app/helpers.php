<?php

use App\Helpers\SystemConfigHelper;

if (!function_exists('system_config')) {
    function system_config($key = null)
    {
        return SystemConfigHelper::get($key);
    }
}

if (!function_exists('hospital_name')) {
    function hospital_name()
    {
        return SystemConfigHelper::hospitalName();
    }
}

if (!function_exists('program_manager')) {
    function program_manager()
    {
        return SystemConfigHelper::programManager();
    }
}

if (!function_exists('app_logo')) {
    function app_logo()
    {
        return SystemConfigHelper::appLogo();
    }
}

if (!function_exists('hospital_logo')) {
    function hospital_logo()
    {
        return SystemConfigHelper::hospitalLogo();
    }
}