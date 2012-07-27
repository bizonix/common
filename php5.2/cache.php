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

class Cache
{
    // Some static properties.
    
    protected function __construct() { }
    protected static $_con = null;
    protected static $_driver = null;
    
    // Initialize the cache connection.
    
    public static function initialize($servers = array('127.0.0.1'))
    {
        if (class_exists('Memcached'))
        {
            self::$_driver = 'Memcached';
            self::$_con = new Memcached();
            foreach($servers as $server)
            {
                $server = explode(':', $server);
                $host = $server[0];
                $port = isset($server[1]) ? $server[1] : 11211;
                $weight = isset($server[2]) ? $server[2] : 100;
                self::$_con->addServer($host, $port, $weight);
            }
        }
        elseif (class_exists('Memcache'))
        {
            self::$_driver = 'Memcache';
            self::$_con = new Memcache();
            foreach($servers as $server)
            {
                $server = explode(':', $server);
                $host = $server[0];
                $port = isset($server[1]) ? $server[1] : 11211;
                $weight = isset($server[2]) ? $server[2] : 100;
                self::$_con->addServer($host, $port, true, $weight);
            }
        }
        else
        {
            throw new CommonCacheException('Memcached extension is not available.');
        }
    }
    
    // GET method.
    
    public static function get($key)
    {
        return self::$_con->get($key);        
    }
    
    // SET method.
    
    public static function set($key, $value = null, $ttl = 3600)
    {
        if (self::$_driver === 'Memcached')
        {
            return self::$_con->set($key, $value, $ttl);
        }
        else
        {
            return self::$_con->set($key, $value, 0, $ttl);
        }
    }
    
    // DELETE method.
    
    public static function delete($key)
    {
        return self::$_con->delete($key);
    }
    
    // Callback cache. Closures are not supported.
    
    public static function callback($callback, $args = array(), $expires = 300)
    {
        // Create a unique ID for this callback item.
        
        $key = '@CallBack:' . md5(serialize($callback) . "\n" . serialize($args));
        
        // Look up the cached value.
        
        $value = self::get($key);
        
        // If not found, call the callback and cache the return value.
        
        if ($value === false)
        {
            $value = call_user_func_array($callback, $args);
            self::set($key, $value, $expires);
        }
        
        // Return the value.
        
        return $value;
    }
}

class CommonCacheException extends CommonException { }
