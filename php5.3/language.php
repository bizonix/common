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

class Language
{
    // Some instance variables.
    
    protected static $_dir = false;
    protected $_dir_override = false;
    protected $_language = false;
    protected $_translations = false;
    
    // Set the directory for translation files.
    
    public static function set_dir($dir)
    {
        self::$_dir = rtrim($dir, '/');
    }
    
    // Constructor.
    
    public function __construct($language, $dir_override = false)
    {
        $this->_language = strtolower($language);
        $this->_dir_override = $dir_override;
    }
    
    // Shortcut for translations with no arguments.
    
    public function __get($key)
    {
        return $this->translate($key);
    }
    
    // Shortcut for translations with arguments.
    
    public function __call($key, $args)
    {
        return $this->translate($key, $args);
    }
    
    // Translate method.
    
    public function translate($key, $args = array())
    {
        // Load the translation set.
        
        if ($this->_translations === false) $this->_load_translations();
        
        // If a translation does not exist, return null.
        
        if (!array_key_exists($key, $this->_translations)) return null;
        
        // If the first argument (not counting the key) is not an array, get all arguments.
        
        if (!is_array($args))
        {
            $args = func_get_args();
            array_shift($args);
        }
        
        // If no arguments are given, return the raw translation.
        
        if (!count($args)) return $this->_translations[$key];
        
        // Otherwise, substitute the arguments and return.
        
        return vsprintf($this->_translations[$key], $args);
    }
    
    // Translation set loader.
    
    protected function _load_translations()
    {
        // Load up the language file.
        
        if ($this->_dir_override === false)
        {
            $filename = self::$_dir . '/' . $this->_language . '.php';
        }
        else
        {
            $filename = $this->_dir_override . '/' . $this->_language . '.php';
        }
        
        // Include the file and extract the translations.
        
        if (file_exists($filename))
        {
            include $filename;
            if (isset($translations) && is_array($translations))
            {
                $this->_translations = $translations;
            }
            else
            {
                throw new LanguageException($filename . ' does not contain $translations.');
            }
        }
        else
        {
            throw new LanguageException($filename . ' does not exist.');
        }
    }
}

class LanguageException extends Exception { }
