
Security Class
--------------

This class makes it easier to perform several security-related tasks.
On its own, it cannot make your application secure; the security of your program depends on a lot of other factors.

### Security::filter()

Arguments:

  - mixed $input
  - string $filter

Explanation:

  - This method sanitizes the given input according to the given instructions. The result is returned.
  - Available filters:
    - `int` : The input is coerced into an integer, or a float if it is greater than `INT_MAX`.
    - `float` : The input is coerced into a float. This is identical to `(float)$input`.
    - `escape` : The input is passed through `htmlspecialchars`, assuming UTF-8 encoding.
      If the input is not valid UTF-8, `false` is returned.
    - `strip` : The input is stripped of all HTML tags, assuming UTF-8 encoding.
      If the input is not valid UTF-8, `false` is returned.
    - `filename` : The input is treated as a filename, encoded in UTF-8.
      Any character that is not safe to use as a filename, either in Windows or in Unix-like systems,
      are either removed, replaced with `_`, or replaced with a similar character (e.g. from `{}` to `()`).
      In addition, excessively long filenames are truncated to 100 Unicode characters,
      and `.php` extensions are replaced with `.phps` (the correct extension for PHP source code files)
      to prevent the web server from executing uploaded files.
      If the input is not valid UTF-8, `false` is returned.

### Security::validate()

Arguments:

  - mixed $input
  - string $rules

Explanation:

  - This method returns `true` if the input satisfies the given rules, and `false` otherwise.
  - Rules should be separated by commas.
  - The first rule should be one of the following:
    - `int` : Checks whether the input is a positive integer (greater than or equal to 0). Leading zeroes are not permitted.
    - `ip` : Checks whether the input is a valid IP address.
    - `email` : Checks whether the input is a valid e-mail address.
    - `url` or `uri` : Checks whether the input is a valid URL.
    - `unicode` or `utf-8` : Checks whether the input is valid UTF-8.
    - `string` : Checks whether the input is a string. Useless on its own, but can be used with a subfilter (see below).
    - `alpha` : Checks whether the input is composed of Roman alphabets only.
    - `alnum` : Checks whether the input is composed of Roman alphabets and/or Arabic numerals only.
    - `hex` : Checks whether the input is composed of hexademical digits only.
  - The following subrules are available.
    - `min=X` and `max=X` : For integers, enforce minimum and maximum values. For strings, enforce minimum and maximum lengths.
      For UTF-8 strings, characters are counted. For other strings, bytes are counted.
    - `len=X` : For strings, enforce the exact length.
      For UTF-8 strings, characters are counted. For other strings, bytes are counted.
    - `ipv4`, `ipv6`, `noprivate`, `noreserved` : For restricting IP addresses to specific ranges.
    - `domain` : for e-mail addresses and URLs, enforce a specific domain.

Examples:

    Security::validate(42, 'int,min=20,max=60');                       // true
    Security::validate('test@example.com', 'email');                   // true
    Security::validate('test@example.com', 'email,domain=other.com');  // false
    Security::validate('hello world', 'alpha');                        // false
    Security::validate('abcdef176532', 'hex,len=12');                  // true

### Security::get_random()

Arguments:

  - int $length

Explanation:

  - This method returns a random hexademical string with the specified length.
  - This method will attempt to use `/dev/urandom` as source.
    If this fails, it will use `mt_rand()` repeatedly to accumulate sufficient entropy.
    In any case, the source of randomness is not directly returned, but passed through a hashing function.
  - The longest random string that can be generated at once is 128 bytes (the output of SHA512).
    If you request a longer random string, this process will be repeated, 128 bytes at a time.
