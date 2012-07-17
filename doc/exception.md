
Exception Class
---------------

### Introduction

In **PHP 5.3**, all exceptions thrown by other classes in this collection are subclasses of the `\Common\Exception` class.

In **PHP 5.2**, all exceptions thrown by other classes in this collection are subclasses of the `CommonException` class.

### Replacing Errors with Exceptions

The exception class has a static method that allows you to set up an error handler
to turn all PHP errors, warnings, notices, etc. into proper exceptions.
This may be useful if you're fed up with PHP's inconsistent error handling.

In **PHP 5.3**, call `\Common\Exception::replace_errors()`.

Afterward, any error will result in an instance of `\Common\ErrorException` being thrown.
(This exception is _not_ a subclass of `\Common\Exception`, but it is a subclass of the built-in `ErrorException` class.)

In **PHP 5.2**, call `CommonException::replace_errors()`.

Afterward, any error will result in an instance of `CommonErrorException` being thrown.
(This exception is _not_ a subclass of `CommonException`, but it is a subclass of the built-in `ErrorException` class.)

In both versions, you can also catch specific kinds of errors by looking out for the following subclasses:

  - `E_ERROR_Exception`
  - `E_WARNING_Exception`
  - `E_NOTICE_Exception`
  - `E_DEPRECATED_Exception`
  - `E_USER_ERROR_Exception`
  - `E_USER_WARNING_Exception`
  - `E_USER_NOTICE_Exception`
  - `E_USER_DEPRECATED_Exception`
  - `E_RECOVERABLE_Exception`
  - `E_STRICT_Exception`

The custom error handler can be removed by calling `restore_error_handler()` later.

