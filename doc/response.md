
Response Class
--------------

This class makes it easy to send HTML pages, files, JSON, and redirects with appropriate headers.

_Most of the methods described below will terminate the script after they are done._

### Response::send_page()

Arguments:

  - string $content
  - string $content_type (optional)
  - int $expires (optional)

Explanation:

  - This method sends the content to the client and exits.
  - The content type is `text/html` by default.
  - By default, no cache-control headers will be sent.
    If you set `$expires` to 0, caching will be disabled.
    If you set `$expires` to a positive integer, the page will be cached for the same number of seconds.

### Response::send_file()

Arguments:

  - string $filename
  - string $name (optional)
  - int $expires (optional)

Explanation:

  - This method sends the file to the client and exits.
  - If you use output buffering, the content of all buffers will be discarded before the file is sent.
  - The first argument must be the full path to the file that you want to send, e.g. `/srv/www/files/07/09/image.jpg`.
  - The second argument can be used to specify the filename that is visible to the client, e.g. `image.jpg`.
    If it is not specified, the last part of the path will be used.
  - By default, no cache-control headers will be sent.
    If you set `$expires` to 0, caching will be disabled.
    If you set `$expires` to a positive integer, the page will be cached for the same number of seconds.

### Response::send_json()

Arguments:

  - mixed $object

Explanation:

  - This method encodes the supplied object as JSON, sends it to the client, and exits.
  - Caching will be disabled.

### Response::redirect()

Arguments:

  - string $location
  - bool $permanent (optional)

Explanation:

  - This method redirects the client to `$location` and exits.
  - If `$permanent` is TRUE, a 301 redirect will be issued. Otherwise, a 302 redirect will be issued.
    The default value is FALSE.

### Response::not_found()

Arguments:

  - string $message (optional)

Explanation:

  - This method produces a `404 Not Found` header, displays `$message` (if any), and exits.
  - If `$message` is not supplied, a blank page with the words `404 Not Found` will be displayed.
  - You can customize the message by using the two methods explained below.

### Response::not_found_set_default_message()

Arguments:

  - string $message

Explanation:

  - You can use this method to pre-set the message that will be shown when you call `not_found()`.
    That way, you don't have to generate the message every time you call `not_found()`.
    This makes it easy for you to set up a site-wide 404 error page.

### Response::not_found_set_default_callback()

Arguments:

  - callback $callback

Explanation:

  - This method works in the same way as the method above, but it takes a function, method, or closure as an argument.
    The callback will be evaluated when you call `not_found()`.
    This is useful if you need to set up a site-wide 404 error page but customize it for different contexts,
    since the method above can only handle a static message.

### Response::send_http_status_code()

Arguments:

  - int $code

Explanation:

  - This method produces an HTTP status message, e.g. `304 Not Modified` if `$code` is 304.
  - It _does not_ exit after producing the status message.
    It is up to you to generate an appropriate page and headers to display after the status message.
