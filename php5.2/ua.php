<?php

/**
 * -----------------------------------------------------------------------------
 *              C O M M O N  L I B R A R I E S  ( P H P  5 . 2 )
 * -----------------------------------------------------------------------------
 * 
 * @package    Common
 * @author     Kijin Sung <kijin@kijinsung.com>
 * @copyright  (c) 2012, Kijin Sung <kijin@kijinsung.com>
 * @license    GPL v3 <http://www.opensource.org/licenses/gpl-3.0.html>
 * @link       http://github.com/kijin/common
 * @version    201207.1
 * 
 * -----------------------------------------------------------------------------
 * 
 * Copyright (c) 2012, Kijin Sung <kijin@kijinsung.com>
 * 
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * -----------------------------------------------------------------------------
 */

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
