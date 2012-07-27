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

class Session
{
    public static function start($name = 'PHPSESSID')
    {
        session_name($name);
        session_start();
        if (isset($_SESSION['last_refresh']) && $_SESSION['last_refresh'] < time() - 300)
        {
            $_SESSION['last_refresh'] = time();
            session_regenerate_id();
        }
    }
    
    public static function refresh()
    {
        session_regenerate_id();
    }
    
    public static function login($id)
    {
        $_SESSION['login'] = $id;
        $_SESSION['logout_token'] = Security::get_random(32);
        $_SESSION['last_refresh'] = time();
        session_regenerate_id();
    }
    
    public static function logout()
    {
        session_destroy();
    }
    
    public static function get_login_id()
    {
        return isset($_SESSION['login']) ? $_SESSION['login'] : null;
    }
    
    public static function get_logout_token()
    {
        return isset($_SESSION['logout_token']) ? $_SESSION['logout_token'] : '';
    }
    
    public static function add_token($token)
    {
        isset($_SESSION['tokens']) or $_SESSION['tokens'] = array();
        $_SESSION['tokens'][] = $token;
    }
    
    public static function check_token($token)
    {
        isset($_SESSION['tokens']) or $_SESSION['tokens'] = array();
        return in_array($token, $_SESSION['tokens']);
    }
    
    public static function clear_tokens()
    {
        $_SESSION['tokens'] = array();
    }
}
