
File Class
----------

This class provides an object-oriented interface for reading and writing to files.

_This class needs to be instantiated. Its methods are not static._

### Constructor

Arguments:

  - string $filename
  - string $mode (optional)

Explanation:

  - The mode should be the same as what you would pass to `fopen()`.
  - If the mode is not specified, the default value is `r` (read-only).
    If you want to read and write freely, you should change this to `r+` or `w+`.
  - If the mode contains `r` and the file does not exist, `FileNotFoundException` will be thrown.
  - If the mode contains `x` and the file already exists, `FileAlreadyExistsException` will be thrown.
  - On any other error, `FileException` will be thrown.

Usage:

    $fp = new File('/path/to/filename.txt', 'r');
    while (!$fp->is_end())
    {
        echo $fp->read_line();
    }

### Properties

  - `name`: The name of the file, including the full path.
  - `size`: The size of the file, in bytes.
  - `position`: The current position of the pointer.

### ->set_line_ending()

Arguments:

  - string $line_ending

Explanation:

  - You can use this method to change the line ending to use with `write_line()`.
    For consistency, it is recommended that you do this as soon as opening the file.
  - The default line ending is `\n` (UNIX style).
  - Permissible values are `\n`, `\r\n`, and `\r`.

### ->seek()

Arguments:

  - int $offset

Explanation:

  - This method moves the pointer by `$offset` bytes, relative to the current position.

### ->seek_to()

Arguments:

  - int $offset

Explanation:

  - This method moves the pointer to `$offset` bytes, starting from the beginning of the file.

### ->rewind()

Arguments: none.

Explanation:

  - This method moves the pointer to the beginning of the file.

### ->end()

Arguments: none.

Explanation:

  - This method moves the pointer to the end of the file.

### ->is_end()

Arguments: none.

Explanation:

  - This method returns TRUE if the pointer is currently at the end of the file, and FALSE otherwise.

### ->read()

Arguments:

  - int $bytes

Explanation:

  - This method reads and returns `$bytes` bytes of data from the file.

### ->read_line()

Arguments: none.

Explanation:

  - This method reads and returns one line from the file.

### ->write()

Arguments:

  - string $data

Explanation:

  - This method writes `$data` to the file.

### ->write_line()

Arguments:

  - string $data

Explanation:

  - This method writes `$data` to the file, followed by the line ending.

### ->flush()

Arguments: none.

Explanation:

  - This method flushes the buffer to disk.

### ->passthru()

Arguments: none.

Explanation:

  - This method displays the remainder of the file, starting from the current position.

### ->truncate()

Arguments:

  - string $size (optional)

Explanation:

  - This method resizes the file to `$size` bytes.
  - The size is 0 by default.

### ->stat()

Arguments: none.

Explanation:

  - This method is equivalent to `fstat()`.

### ->lock_shared()

Arguments: none.

Explanation:

  - This method acquires a shared advisory lock on the file, suitable for reading.

### ->lock_exclusive()

Arguments: none.

Explanation:

  - This method acquires an exclusive advisory lock on the file, suitable for writing.

### ->unlock()

Arguments: none.

Explanation:

  - This method releases all locks.

### ->close()

Arguments: none.

Explanation:

  - This method closes the file pointer.

### Exceptions

  - `FileException`: All errors, including the below.
  - `FileNotFoundException`: Thrown when attempting to open a nonexistent file for reading.
  - `FileAlreadyExistsException`: Thrown when attempting to create a file that already exists.
  - `FileClosedException`: Thrown when attempting any operation after calling `close()`.
