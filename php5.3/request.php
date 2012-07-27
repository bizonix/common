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

class Request
{
    // GET wrapper.
    
    public static function get($name, $filter = '')
    {
        if (!isset($_GET[$name])) return null;
        $value = get_magic_quotes_gpc() ? stripcslashes($_GET[$name]) : $_GET[$name];
        return $filter ? Security::filter($value, $filter) : $value;
    }
    
    // POST wrapper.
    
    public static function post($name, $filter = '')
    {
        if (!isset($_POST[$name])) return null;
        $value = get_magic_quotes_gpc() ? stripcslashes($_POST[$name]) : $_POST[$name];
        return $filter ? Security::filter($value, $filter) : $value;
    }
    
    // Uploaded file wrapper.
    
    public static function file($name)
    {
        return new Upload($name);
    }
    
    // Fetch some information about the request.
    
    public static function info($type)
    {
        switch ($type)
        {
            case 'http_version':
                return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 0;
                
            case 'protocol':
                return (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                
            case 'method':
                return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
                
            case 'domain':
            case 'host':
                return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
                
            case 'uri':
            case 'url':
                return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
                
            case 'time':
                return isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
                
            case 'ip':
                return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
                
            case 'user_agent':
                return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
                
            case 'referer':
                return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
                
            case 'ajax':
                return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true : false;
                
            case 'keepalive':
                if (isset($_SERVER['HTTP_CONNECTION']) && strtolower($_SERVER['HTTP_CONNECTION']) === 'keep-alive')
                {
                    return isset($_SERVER['HTTP_KEEP_ALIVE']) ? intval($_SERVER['HTTP_KEEP_ALIVE']) : true;
                }
                else
                {
                    return false;
                }
            
            case 'old_browser':
                if (!isset($_SERVER['HTTP_USER_AGENT'])) return false;
                return preg_match('/MSIE ([1-7])\\./', $_SERVER['HTTP_USER_AGENT'], $matches) ? $matches[1] : false;
        }
    }
}
