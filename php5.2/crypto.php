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
