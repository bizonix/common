<?php

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
