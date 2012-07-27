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

// The base exception class.

class CommonException extends Exception
{
    // Once you call this method, all PHP errors become exceptions.
    
    public static function replace_errors()
    {
        if (!function_exists('common_error_handler_callback'))
        {
            function common_error_handler_callback($errno, $errstr, $errfile, $errline)
            {
                switch ($errno)
                {
                    case E_ERROR:
                        throw new E_ERROR_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_WARNING:
                        throw new E_WARNING_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_NOTICE:
                        throw new E_NOTICE_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_DEPRECATED:
                        throw new E_DEPRECATED_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_USER_ERROR:
                        throw new E_USER_ERROR_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_USER_WARNING:
                        throw new E_USER_WARNING_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_USER_NOTICE:
                        throw new E_USER_NOTICE_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_USER_DEPRECATED:
                        throw new E_USER_DEPRECATED_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_RECOVERABLE_ERROR:
                        throw new E_RECOVERABLE_Exception($errstr, 0, $errno, $errfile, $errline);
                    case E_STRICT:
                        throw new E_STRICT_Exception($errstr, 0, $errno, $errfile, $errline);
                    default:
                        throw new CommonErrorException($errstr, 0, $errno, $errfile, $errline);
                }
            }
        }
        set_error_handler('common_error_handler_callback', -1);
    }
}

// Error replacement exception classes.

class CommonErrorException extends ErrorException { }
class E_ERROR_Exception extends CommonErrorException { }
class E_WARNING_Exception extends CommonErrorException { }
class E_NOTICE_Exception extends CommonErrorException { }
class E_DEPRECATED_Exception extends CommonErrorException { }
class E_USER_ERROR_Exception extends E_ERROR_Exception { }
class E_USER_WARNING_Exception extends E_WARNING_Exception { }
class E_USER_NOTICE_Exception extends E_NOTICE_Exception { }
class E_USER_DEPRECATED_Exception extends E_DEPRECATED_Exception { }
class E_RECOVERABLE_Exception extends CommonErrorException { }
class E_STRICT_Exception extends CommonErrorException { }
