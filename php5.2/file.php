<?php

class File
{
    // Some instance variables.
    
    protected $_filename;
    protected $_pointer;
    protected $_line_ending = "\n";
    
    // Constructor.
    
    public function __construct($filename, $mode = 'r')
    {
        // If the mode is 'r', check that the file exists.
        
        if (strpos($mode, 'r') !== false && !file_exists($filename))
        {
            throw new FileNotFoundException('File not found: ' . $filename);
        }
        
        // If the mode is 'x', check that the file DOES NOT exist.
        
        if (strpos($mode, 'x') !== false && file_exists($filename))
        {
            throw new FileAlreadyExistsException('File already exists: ' . $filename);
        }
        
        // Store the filename and open the file.
        
        $this->_filename = $filename;
        $this->_pointer = fopen($filename, $mode);
        
        // Throw a generic exception on unexpected failure.
        
        if (!$this->_pointer) throw new FileException('Failed to open file: '. $filename);
    }
    
    // Return the filename when cast to string.
    
    public function __toString()
    {
        return $this->_filename;
    }
    
    // Generic getter function.
    
    public function __get($name)
    {
        switch ($name)
        {
            case 'name':
                return $this->_filename;
            case 'size':
                return filesize($this->_filename);
            case 'position':
                return ftell($this->_pointer);
            default:
                throw new FileException('Property does not exist: ' . $name);
        }
    }
    
    // Set the line ending.
    
    public function set_line_ending($line_ending)
    {
        if (!in_array($line_ending, array("\n", "\r\n", "\r")))
        {
            throw new FileException('Line ending must be either \\n, \\r\\n, or \\r.');
        }
        
        $this->_line_ending = $line_ending;
    }
    
    // Seek to a position relative to the current position.
    
    public function seek($offset)
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fseek($this->_pointer, $offset, SEEK_CUR);
    }
    
    // Seek to an absolute position.
    
    public function seek_to($offset)
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fseek($this->_pointer, $offset, SEEK_SET);
    }
    
    // Move the pointer to the beginning of the file.
    
    public function rewind()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return rewind($this->_pointer);
    }
    
    // Move the pointer to the end of the file.
    
    public function end()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fseek($this->_pointer, 0, SEEK_END);
    }
    
    // Check whether the pointer is at the end of the file.
    
    public function is_end()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return feof($this->_pointer);
    }
    
    // Read a number of bytes from the file.
    
    public function read($bytes)
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fread($this->_pointer, $bytes);
    }
    
    // Read a line from the file.
    
    public function read_line()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fgets($this->_pointer);
    }
    
    // Write data to the file.
    
    public function write($data)
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fwrite($this->_pointer, $data);
    }
    
    // Write a line to the file.
    
    public function write_line($data)
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fwrite($this->_pointer, $data . $this->_line_ending);
    }
    
    // Flush all buffers to disk.
    
    public function flush()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fflush($this->_pointer);
    }
    
    // Display the contents of the file, beginning at the current position.
    
    public function passthru()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fpassthru($this->_pointer);
    }
    
    // Truncate the file.
    
    public function truncate($size = 0)
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return ftruncate($this->_pointer, $size);
    }
    
    // Get some information about the file.
    
    public function stat()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return fstat($this->_pointer);
    }
    
    // Acquire a shared lock for reading.
    
    public function lock_shared()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return flock($this->_pointer, LOCK_SH);
    }
    
    // Acquire an exclusive lock for writing.
    
    public function lock_exclusive()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return flock($this->_pointer, LOCK_EX);
    }
    
    // Unlock the file.
    
    public function unlock()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        return flock($this->_pointer, LOCK_UN);
    }
    
    // Close.
    
    public function close()
    {
        if (!$this->_pointer) throw new FileClosedException('Attempted operation on a closed file.');
        $status = fclose($this->_pointer);
        $this->_pointer = null;
        return $status;
    }
    
    // Destructor.
    
    public function __destruct()
    {
        @fclose($this->_pointer);
    }
}

// Exceptions.

class FileException extends Exception { }
class FileNotFoundException extends FileException { }
class FileAlreadyExistsException extends FileException { }
class FileClosedException extends FileException { }
