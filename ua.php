<?php

namespace Common;

class UA
{
    // This method returns true if the client seems to be a mobile device, and false otherwise.

    public static function is_mobile()
    {
        // Check for some telltale headers.
        
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) return true;
        if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) return true;
        
        // Grab the user-agent string. If none is provided, we play safe and assume it's not mobile.
        
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : null;
        if (is_null($ua)) return false;
        
        // Quickly check for popular devices, as well as the telltale "mobile" keyword.
        // IE Mobile and Mobile Safari will be caught by the "mobile" keyword.
        
        if (preg_match('/android|ip(hone|ad|od)|blackberry|nokia|palm|mobile/', $ua)) return true;
        
        // If the user-agent string contains common desktop OS names, the client is probably not mobile.
        // Windows Mobile and Android may also contain these names, but they're already caught above.
        
        if (preg_match('/windows|linux|os [x9]|bsd/', $ua)) return false;
        
        // Check for common mobile browsers, platforms, device identifiers, and manufacturer names.
        
        if (preg_match('/kindle|opera (mini|mobi)|polaris|netfront|fennec|symbianos|webos/', $ua)) return true;
        if (preg_match('/s[pgc]h-|lgtelecom|sonyericsson|vodafone|maemo|minimo|bada/', $ua)) return true;
        
        // If all checks fail, default to not mobile.
        
        return false;
    }

    // This method returns true if the client seems to be a robot, and false otherwise.

    public static function is_robot()
    {
        // Grab the user-agent string. If none is provided, we play safe and assume it's human.
        
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : null;
        if (is_null($ua)) return false;
        
        // Check for common robot user agents. "bot" catches Googlebot as well as a lot of obscure robots.
        
        if (preg_match('/bot|msnbot|slurp|facebook(externalhit|scraper)|ask jeeves|teoma|baidu|daumoa|naverbot|lycos/', $ua)) return true;
        return false;
    }
}
