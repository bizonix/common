
MIME Class
----------

This class provides several static methods to help you juggle file types.

### MIME::from_extension()

Arguments:

  - string $extension

Explanation:

  - This method returns a string containing the MIME type that matches an extension.
  - If no MIME type matches a given extension, `application/octet-stream` will be returned.

Usage:

    echo \Common\MIME::from_extension('jpg');  // image/jpeg

### MIME::from_filename()

Arguments:

  - string $filename

Explanation:

  - This method is similar to the method above, except that it takes a full filename.
  - If no MIME type matches a given filename, `application/octet-stream` will be returned.

Usage:

    echo \Common\MIME::from_filename('filename.jpg');  // image/jpeg

### MIME::get_extension()

Arguments:

  - string $mime_type

Explanation:

  - This method returns the extension that is most commonly used for a MIME type.
    You can think of this method as the reverse of `from_extension()` above.
  - If no extension is known for the given MIME type, this method returns NULL.

Usage:

    echo \Common\MIME::get_extension('image/jpeg');  // jpg
