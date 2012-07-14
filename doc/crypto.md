
Crypto Class
------------

This class offers shortcuts to some of the most commonly used MCrypt functions.
You can use this class to encrypt and decrypt strings much more easily than with raw MCrypt.
Initialization vectors and key resizing are automatically handled,
and the plaintext is also gzip-compressed to increase randomness and reduce storage needs.

_This class needs to be instantiated. Its methods are not static._

### Constructor

Arguments:

  - string $key (optional)
  - string $cipher (optional)
  - string $mode (optional)

Explanation:

  - You can supply the key (password), cipher, and mode at the time of instantiation,
    or you can supply them later using one or more of the methods listed below.
  - Only the key needs to be specified in order for the Crypto class to work.
    The cipher defaults to `rijndael-256` (AES256), and the mode defaults to `cbc`.

Usage:

    $crypto = new Crypto($password);
    $ciphertext = $crypto->encrypt($plaintext);
    $plaintext = $crypto->decrypt($ciphertext);

### ->set_key()

Arguments:

  - string $key

Explanation:

  - Use this method to specify the key (password) after instantiating the Crypto class.
    This is an alternative to specifying the key in the constructor.

### ->set_cipher()

Arguments:

  - string $cipher

Explanation:

  - Use this method to specify the cipher after instantiating the Crypto class.
    This is an alternative to specifying the cipher in the constructor.
  - If the cipher is not specified, it defaults to `rijndael-256` (AES256).
    Please refer to the PHP documentation for a list of available ciphers.

### ->set_mode()

Arguments:

  - string $mode

Explanation:

  - Use this method to specify the mode after instantiating the Crypto class.
    This is an alternative to specifying the mode in the constructor.
  - If the mode is not specified, it defaults to `cbc`.
    Please refer to the PHP documentation for a list of available modes.

### ->encrypt()

Arguments:

  - string $plaintext
  - bool $return_as_blob (optional)

Explanation:

  - This method takes the plaintext, encrypts it, and returns the encrypted result (ciphertext).
  - If the second argument is `TRUE`, this method will return the ciphertext as a binary string.
    This may contain unprintable characters such as null bytes.
    If this argument is `FALSE`, the ciphertext will be base64-encoded.
    If you want to store the ciphertext in a text file, cookie, etc,
    it is recommended that you set the second argument to `FALSE`.
    The default value is `FALSE`.

### ->decrypt()

Arguments:

  - string $ciphertext
  - bool $input_is_blob (optional)

Explanation:

  - This method takes the ciphertext, decrypts it, and returns the plaintext.
  - If the second argument is `TRUE`, the ciphertext will be treated as a binary string.
    If it is `FALSE`, the ciphertext will be treated as a base64-encoded string.
    You must use the same option here that you used with `encrypt()`, otherwise you will not be able to decrypt your strings.
    The default value is `FALSE`.

