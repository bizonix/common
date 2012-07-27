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

class Crypto
{
    // Some instance variables.
    
    protected $_key = false;
    protected $_cipher = 'rijndael-256';
    protected $_mode = 'cbc';
    
    // Constructor with optional parameters.
    
    public function __construct($key = false, $cipher = false, $mode = false)
    {
        if ($key !== false) $this->_key = $key;
        if ($cipher !== false) $this->_cipher = $cipher;
        if ($mode !== false) $this->_mode = $mode;
    }
    
    // Set the encryption key.
    
    public function set_key($key)
    {
        $this->_key = $key;
    }
    
    // Set the encryption cipher.
    
    public function set_cipher($cipher)
    {
        $this->_cipher = $cipher;
    }

    // Set the encryption mode.
    
    public function set_mode($mode)
    {
        $this->_mode = strtolower($mode);
    }

    // Encrypt method.
    
    public function encrypt($plaintext, $return_as_blob = false)
    {
        // Check if the key has been entered.
        
        if ($this->_key === false) throw new CryptoException('Please call set_key() first.');
        
        // Compress the plaintext.
        
        $plaintext = gzcompress($plaintext);
        
        // Create an IV.
        
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->_cipher, $this->_mode), MCRYPT_DEV_URANDOM);
        
        // Resize the key.
        
        $key = substr(hash('sha256', $this->_key), 0, mcrypt_get_key_size($this->_cipher, $this->_mode));
        
        // Encrypt, and attach the IV to the ciphertext.
        
        $ciphertext = $iv . mcrypt_encrypt($this->_cipher, $key, $plaintext, $this->_mode, $iv);
        
        // Return the result.
        
        return $return_as_blob ? $ciphertext : base64_encode($ciphertext);
    }
    
    // Decrypt method.
    
    public function decrypt($ciphertext, $input_is_blob = false)
    {
        // Check if the key has been entered.
        
        if ($this->_key === false) throw new CryptoException('Please call set_key() first.');
        
        // If input is not a blob, decode base64 first.
        
        if (!$input_is_blob)
        {
            $ciphertext = base64_decode($ciphertext);
            if ($ciphertext === false) throw new CryptoException('Invalid base-64 encoding.');
        }
        
        // Detach the IV from the ciphertext.
        
        $ivsize = mcrypt_get_iv_size($this->_cipher, $this->_mode);
        $iv = substr($ciphertext, 0, $ivsize);
        
        // Resize the key.
        
        $key = substr(hash('sha256', $this->_key), 0, mcrypt_get_key_size($this->_cipher, $this->_mode));
        
        // Decrypt.
        
        $plaintext = mcrypt_decrypt($this->_cipher, $key, substr($ciphertext, $ivsize), $this->_mode, $iv);
        
        // Decompress the plaintext.
        
        $plaintext = gzuncompress($plaintext);
        
        // Return the result.
        
        return $plaintext; //rtrim($plaintext, "\0");
    }
}

class CryptoException extends Exception { }
