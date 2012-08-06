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
 * @version    201207.2
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

class Router
{
    // Shortcut definitions.
    
    protected static $_shortcuts = array('(alpha)', '(alnum)', '(num)', '(int)', '(hex)', '(any)');
    protected static $_regexes = array('([a-zA-Z]+)', '([a-zA-Z0-9]+)', '([0-9]+)', '([1-9][0-9]*)', '([a-fA-F0-9]+)', '([a-zA-Z0-9._-]+)');
    
    // The dispatcher.
    
    public static function dispatch($routes, $url_override = false)
    {
        // Fetch some information about the current request.
        
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        
        if ($url_override === false)
        {
            $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $url = substr($url, 0, (($pos = strpos($url, '?')) !== false) ? $pos : strlen($url));
            $url = substr($url, strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')));
        }
        else
        {
            if (!$url_override || $url_override[0] !== '/') throw new RouterException('Invalid URL override: ' . $url_override);
            $url = $url_override;
        }
        
        // Try to match routes to the current request.
        
        foreach ($routes as $def => $callback)
        {
            // Parse the route definition.
            
            if ($def[0] === '/')
            {
                $def_method = null;
                $def_host = null;
                $def_url = $def;
            }
            else
            {
                $first_slash = strpos($def, '/');
                if (!$first_slash) throw new RouterException('Invalid route: ' . $def);
                $prefixes = explode(' ', substr($def, 0, $first_slash));
                $def_method = (isset($prefixes[0]) && !empty($prefixes[0])) ? $prefixes[0] : null;
                $def_host = (isset($prefixes[1]) && !empty($prefixes[1])) ? $prefixes[1] : null;
                $def_url = substr($def, $first_slash);
            }
            
            // Try to match the request method, hostname, and the URL.
            
            if (!is_null($def_method) && $def_method !== $method) continue;
            if (!is_null($def_host) && $def_host !== $host) continue;
            if (!preg_match('#^' . str_replace(self::$_shortcuts, self::$_regexes, $def_url) . '$#', $url, $args)) continue;
            
            // Turn captured parameters into a usable form.
            
            array_shift($args);
            $args = array_map('urldecode', $args);
            
            // Parse the callback.
            
            if (is_string($callback) && ($arrow = strpos($callback, '->')) !== false)  // Instance method.
            {
                list($class_name, $method) = explode('->', $callback);
                return call_user_func_array(array(new $class_name, $method), $args);
            }
            elseif (is_callable($callback))  // Static method or closure.
            {
                return call_user_func_array($callback, $args);
            }
            elseif (is_string($callback) && $callback[0] === '@')  // String literal.
            {
                return substr($callback, 1);
            }
            else  // Everything else is invalid.
            {
                return false;
            }
        }
        
        // If we're here, it means we couldn't find a matching route.
        
        return false;
    }
    
    // URL constructor.
    
    public static function get_url( /* args */ )
    {
        static $base = false;
        if (!$base) $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $args = func_get_args();
        return str_replace('//', '/', $base . '/' . implode('/', $args));
    }
}

class CommonRouterException extends CommonException { }
