
Router Class
------------

This class helps you map URLs to controllers, if you use a front controller design pattern (pass every request through index.php).

### Router::dispatch()

Arguments:

  - array $routes
  - string $url_override (optional)

Explanation:

  - Please see below for examples of `$routes`.
  - By default, the value of `$_SERVER['REQUEST_URI']` will be used, minus the part the precedes the location of the current script.
    You can override this by supplying a different URL as `$url_override`.

Return Values:

  - When using a controller callback, this method returns whatever is returned by the controller.
    Normally, the script will never reach this point, because the controller should produce the output and exit.
  - When using the `@value` format (see below), this method will return the string following the `@` sign.
    It is up to you to interpret the return value and take appropriate action.
  - If no route matches the current request, this method will return FALSE.
    Normally, you should watch out for this condition and call `Response::not_found()`.
  - A `RouterException` will be thrown if one or more route definitions are syntactically incorrect.

Apache Rewrite Rule Example:

    RewriteEngine On
    RewriteCond $1 !^(static/|favicon\.ico|robots\.txt)
    RewriteRule ^(.*)$ index.php [L]

Route Definition Example:

    Router::dispatch(array(
        '/'             => 'HomeController->index',
        '/post/(num)    => 'BlogController->viewPost',      // The post number is passed as argument to viewPost().
        'POST /comment' => 'BlogController->newComment',    // Only when request method is POST.
    ));

Route Definition Syntax:

  - Each member of the supplied array should be a key-value pair such as below:

    '[METHOD] [HOST]/parts/of/address/(placeholder1)/(placeholder2)/etc' => 'callback',

  - If a method (GET, POST, HEAD) is specified, only those requests that use the correct method will be
    passed to the callback. This is a good way to distinguish between GET and POST requests to the same URL.

  - If a hostname is specified, only those requests that use the same hostname (domain) will be
    passed to the callback. This is a good way to distinguish between requests to different aliases.

  - Placeholders or regular expressions enclosed in parentheses will be evaluated
    and their actual values passed to the callback as arguments. The callback function or method
    should define the appropriate number of arguments in order to catch them and use them.

  - In case you don't want to write regular expressions in the route definition,
    the following shortcuts are available. These should be good enough for most applications.
    
      - `(alpha)` matches Latin alphabets (e.g. 'myBlog').
      - `(alnum)` matches Latin alphabets and Arabic numerals (e.g. 'myBlog123').
      - `(num)` matches Arabic numerals (e.g. '01234').
      - `(int)` matches positive integers only. This usually works better than `(num)`.
      - `(hex)` matches hexademical digits (e.g. 'fa99c3').
      - `(any)` matches Latin alphabets, Arabic numerals, periods, hyphens, and underscores.

  - The callback can be specified in any of five ways as follows:
  
      - `function_name` : The function will be called. Useful for procedural style.
      - `Class->method` : The class will be instantiated and then the method will be called.
      - `Class::method` : The static method will be called.
      - `array($object, 'method')` : The method will be called on the object supplied.
      - `@value` : The string following the `@` sign will be returned.
