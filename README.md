
Common Libraries
----------------

These are some classes that I've accumulated over the years for use in personal web projects.
I release them here under GPLv3 in the hope that someone else will find them useful,
but I make absolutely no guarantee that these classes will fit your style of web development.

These classes make heavy use of static methods.
This helps simplify a lot of common tasks, but may not be suitable for large projects.

### Getting Started

Include the following line at the top of your script:

    include '/path/to/autoload.php';

### Requirements

If you are stuck with PHP 5.2, use the **php5.2** classes.

If you have PHP 5.3 or later, use the **php5.3** classes.

The two versions are identical except:

  - The 5.3 version uses the `Common` namespace, whereas the 5.2 version does not use any namespaces.
  - Exception classes have slightly different names in 5.3 and 5.2, because of namespacing.
    Please see the documentation for the Exception class for more information.

### Class Reference

  - [AJAX](https://github.com/kijin/common/blob/master/doc/ajax.md)
  - [Cache](https://github.com/kijin/common/blob/master/doc/cache.md)
  - [Crypto](https://github.com/kijin/common/blob/master/doc/crypto.md)
  - [DB](https://github.com/kijin/common/blob/master/doc/db.md)
  - [Exception](https://github.com/kijin/common/blob/master/doc/exception.md)
  - [Fetcher](https://github.com/kijin/common/blob/master/doc/fetcher.md)
  - [File](https://github.com/kijin/common/blob/master/doc/file.md)
  - [Language](https://github.com/kijin/common/blob/master/doc/language.md)
  - [MIME](https://github.com/kijin/common/blob/master/doc/mime.md)
  - [Request](https://github.com/kijin/common/blob/master/doc/request.md)
  - [Timer](https://github.com/kijin/common/blob/master/doc/timer.md)
  - [Upload](https://github.com/kijin/common/blob/master/doc/upload.md)

More documentation will be added in the future.
