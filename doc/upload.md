
Upload Class
-----------

This class provides an object-oriented interface to uploaded files.
You can either instantiate it directly, or access it through `Request::file()`.

_This class needs to be instantiated. Its methods are not static._

### Constructor

Arguments:

  - string $name

Explanation:

  - The constructor must be provided with the name of the `<input type="file">` element
    that was used to upload the file.
  - The constructor will perform several checks on the file.
    It will throw an `UploadException` if no file with the given name has been uploaded,
    if the filename contains invalid characters, or if any other error has occurred during the upload.

### Properties

These properties are read-only.

  - `name`: The sanitized name of the file.
  - `size`: The size of the file, in bytes.
  - `mime_type`: The MIME type of the file, guessed from the filename.
  - `location`: The path to the temporary file if the uploaded file has not been saved yet,
    or the path to the permanent location if it has been saved.

### ->md5()

Arguments: none.

Explanation:

  - This method returns the MD5 hash of the file.

### ->sha1()

Arguments: none.

Explanation:

  - This method returns the SHA1 hash of the file.

### ->hash()

Arguments:

  - string $algo (optional)

Explanation:

  - Use this method to calculate hashes other than MD5 and SHA1.
  - The name of the algorithm must be lower case, e.g. `sha256` or `ripemd160`.
  - If the algorithm is not specified, `sha256` will be assumed.
  - If the specified algorithm is not supported on the system, an `UploadException` will be thrown.
  
### ->is_valid_image()

Arguments: none.

Explanation:

  - This method returns TRUE if the uploaded file seems to be a valid image, and FALSE otherwise.
  - This method requires the GD extension. If GD is not available, this method will throw an `UploadException`.

### ->save()

Arguments:

  - string $destination
  - int $file_mode (optional)
  - bool $mkdir (optional)
  - int $dir_mode (optional)

Explanation:

  - Use this method to move the uploaded file to its permanent location.
  - The permissions for the file defaults to `0644` unless a different `$file_mode` is specified.
  - If the directory in which the file is to be saved does not exist, an `UploadException` will be thrown.
    However, if `$mkdir` is TRUE, this method will automatically create the directory.
    (`$mkdir` is FALSE by default.)
    The permissions of the new directory defaults to `0755` unless a different `$dir_mode` is specified.

Usage Example:

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="upload" />
        <input type="submit" />
    </form>

    $dir = '/path/to/store/uploaded/files';
    $file = new Upload('upload');
    if (!$file->is_valid_image()) throw new Exception('Not a valid image!');
    $file->save($dir . '/' . $file->name);
