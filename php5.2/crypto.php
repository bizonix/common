<?php

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
    
    public function encrypt($input, $return_as_blob = false)
    {
        // Check if the key has been entered.
        
        if ($this->_key === false) throw new CryptoException('Please call set_key() first.');
        
        // Create an IV.
        
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->_cipher, $this->_mode), MCRYPT_DEV_URANDOM);
        
        // Encrypt, and attach the IV to the ciphertext.
        
        $output = $iv . mcrypt_encrypt($this->_cipher, $this->_key, $input, $this->_mode, $iv);
        
        // Return the result.
        
        return $return_as_blob ? $output : base64_encode($output);
    }
    
    // Decrypt method.
    
    public function decrypt($input, $input_is_blob = false)
    {
        // Check if the key has been entered.
        
        if ($this->_key === false) throw new CryptoException('Please call set_key() first.');
        
        // If input is not a blob, decode base64 first.
        
        if (!$input_is_blob)
        {
            $input = base64_decode($input);
            if ($input === false) throw new CryptoException('Invalid base-64 encoding.');
        }
        
        // Detach the initialize vector from the ciphertext.
        
        $ivsize = mcrypt_get_iv_size($this->_cipher, $this->_mode);
        $iv = substr($input, 0, $ivsize);
        $_ciphertext = substr($input, $ivsize);
        
        // Decrypt.
        
        $output = mcrypt_decrypt($this->_cipher, $this->_key, $_ciphertext, $this->_mode, $iv);
        
        // Return the result.
        
        return $input_is_blob ? $output : rtrim($output, "\0");
    }
}

class CryptoException extends Exception { }
