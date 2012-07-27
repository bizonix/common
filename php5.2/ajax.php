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

class AJAX
{
    // Send some text or HTML content back to the client.
    
    public static function content($content)
    {
        if (Request::info('ajax'))
        {
            Response::send_json(array(
                'status' => 'CONTENT',
                'content' => $content,
            ));
        }
        else
        {
            Response::send_page($content, 'text/plain', 0);
        }
    }
    
    // Redirect the client to another location.
    
    public static function redirect($location)
    {
        if (Request::info('ajax'))
        {
            Response::send_json(array(
                'status' => 'REDIRECT',
                'location' => $location,
            ));
        }
        else
        {
            Response::redirect($location);
        }
    }
    
    // Notify the client of an error.
    
    public static function error($message)
    {
        if (Request::info('ajax'))
        {
            Response::send_json(array(
                'status' => 'ERROR',
                'message' => $message,
            ));
        }
        else
        {
            Response::send_page('ERROR: ' . $message, 'text/plain', 0);
        }
    }
}
