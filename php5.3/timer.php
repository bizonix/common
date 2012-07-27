<?php

/**
 * -----------------------------------------------------------------------------
 *              C O M M O N  L I B R A R I E S  ( P H P  5 . 3 + )
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

namespace Common;

class Timer
{
    // Store timestamps here.
    
    protected static $_timestamps = array();
    
    // Start timing.
    
    public static function start($name = false)
    {
        $timestamp = microtime(true);
        
        if ($name === false)
        {
            $name = 'anon-timer-' . $timestamp;
        }
        
        self::$_timestamps[$name] = $timestamp;
        return $timestamp;
    }
    
    // Stop timing and return the result as a float.
    
    public static function stop($name = false)
    {
        $timestamp = microtime(true);
        $started_timestamp = 0;
        
        if ($name === false)
        {
            if (count(self::$_timestamps))
            {
                $started_timestamp = array_pop(self::$_timestamps);
            }
            else
            {
                return null;
            }
        }
        elseif (array_key_exists($name, self::$_timestamps))
        {
            $started_timestamp = self::$_timestamps[$name];
            unset(self::$_timestamps[$name]);
        }
        else
        {
            return null;
        }
        
        return $timestamp - $started_timestamp;
    }
    
    // Stop timing and return the result as a formatted string.
    
    public static function stop_format($name = false)
    {
        $result = self::stop($name);
        if ($result === null) return $result;
        return number_format($result * 1000, 1, '.', ',') . 'ms';
    }
}
