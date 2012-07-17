<?php

namespace Common;

class Language
{
    // Some instance variables.
    
    protected static $_dir = false;
    protected $_language = false;
    protected $_translations = false;
    
    // Set the directory for translation files.
    
    public static function set_dir($dir)
    {
        self::$_dir = rtrim($dir, '/');
    }
    
    // Constructor.
    
    public function __construct($language)
    {
        $this->_language = strtolower($language);
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
        
        $filename = self::$_dir . '/' . $this->_language . '.php';
        
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
