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

class Upload
{
    // Some instance variables.
    
    protected $_file = array();
    protected $_saved = false;
    
    // Constructor.
    
    public function __construct($name)
    {
        // If the file with the given name has not been uploaded, throw an exception.
        
        if (!isset($_FILES[$name])) throw new UploadException("File '{$name}' has not been uploaded.");
        
        // Retrieve the uploaded file info.
        
        $this->_file = $_FILES[$name];
        
        // Sanitize the filename.
        
        $this->_file['name'] = Security::filter($this->_file['name'], 'filename');
        if ($this->_file['name'] === false || $this->_file['name'] === '')
        {
            throw new UploadException("File '{$name}' has no name, or it has an illegal name.");
        }
        
        // Check the temporary file.
        
        if (!file_exists($this->_file['tmp_name']) || !is_uploaded_file($this->_file['tmp_name']))
        {
            throw new UploadException("File '{$name}' is not associated with a valid temporary file.");
        }
        
        // Check for other errors.
        
        if ($this->_file['error'] > 0)
        {
            throw new UploadException("Unknown error: code {$this->_file['error']}.");
        }
    }
    
    // Shortcuts for some properties.
    
    public function __get($name)
    {
        switch($name)
        {
            case 'name':
                return $this->_file['name'];
            case 'size':
                return $this->_file['size'];
            case 'mime_type':
                return MIME::from_filename($this->_file['name']);
            case 'location':
                return ($this->_saved !== false) ? $this->_saved : $this->_file['tmp_name'];
        }
    }
    
    // MD5 hash method.
    
    public function md5()
    {
        return ($this->_saved !== false) ? md5_file($this->_saved) : md5_file($this->_file['tmp_name']);
    }
    
    // SHA1 hash method.
    
    public function sha1()
    {
        return ($this->_saved !== false) ? sha1_file($this->_saved) : sha1_file($this->_file['tmp_name']);
    }
    
    // Generic hash method.
    
    public function hash($algo = 'sha256')
    {
        if (!in_array($algo, hash_algos())) throw new UploadException("Hash algorithm '{$algo}' is not supported.");
        return ($this->_saved !== false) ? hash_file($algo, $this->_saved) : hash_file($algo, $this->_file['tmp_name']);
    }
    
    // Determines if the uploaded file is a valid image.
    
    public function is_valid_image()
    {
        if (!function_exists('getimagesize')) throw new UploadException("This method requires the GD library.");
        $size = getimagesize(($this->_saved !== false) ? $this->_saved : $this->_file['tmp_name']);
        return (bool)$size;
    }
    
    // Save method.
    
    public function save($destination, $file_mode = 0644, $mkdir = false, $dir_mode = 0755)
    {
        // Error if already saved/moved.
        
        if ($this->_saved) throw new UploadException('The uploaded file has already been saved.');
        
        // Create the directory if it doesn't exist.
        
        $dir = dirname($destination);
        if (!file_exists($dir))
        {
            if ($mkdir)
            {
                mkdir($dir, $dir_mode, true);
            }
            else
            {
                throw new UploadException("Directory '{$dir}' does not exist.");
            }
        }
        
        // Move the uploaded file and chmod it properly.
        
        move_uploaded_file($this->_file['tmp_name'], $destination);
        chmod($destination, $file_mode);
        
        // Mark this file as saved.
        
        $this->_saved = $destination;
    }
    
    // Move method (alias to save).
    
    public function move($destination, $file_mode = 0644, $mkdir = true, $dir_mode = 0755)
    {
        return $this->save($destination, $file_mode, $mkdir, $dir_mode);
    }
}

class UploadException extends Exception { }
