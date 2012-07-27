
Request Class
-------------

This class provides access to GET and POST variables, as well as various information about the current request.

### Request::get()

Arguments:

  - string $name
  - string $filter (optional)

Explanation:

  - This method returns the value of `$_GET[$name]`.
  - If your script is unfortunate enough to be running on a server with magic quotes enabled (i.e. most shared hosting accounts),
    this method will strip extra backslashes before returning the value.
    This allows your application to behave consistently regardless of the quality of the server configuration.
  - You can retrieve a user-submitted value and filter it at the same time, by specifying a filter as the second argument.  
    The filter should be one of the filters supported by the Security class.

### Request::post()

Arguments:

  - string $name
  - string $filter (optional)

Explanation:

  - This method works in the same way as `get()`, except it returns the value of `$_POST[$name]`.
  - Multi-dimensional POST values are not supported.

### Request::file()

Arguments:

  - string $name

Explanation:

  - This method returns an instance of the Upload class, which you can use to work with an uploaded file.
  - You must supply the name of the `<input type="file">` element that was used to upload the file.
  - Please see documentation for the Upload class for more information.

### Request::info()

Arguments:

  - string $type

Explanation:

  - This method returns information about the current request.
  - If information is not available for any reason, this method will return NULL.

Possible values for $type:

  - `http_version`: The version of the protocol used. e.g. `HTTP/1.0`
  - `protocol`: Either `http` or `https`. This value may not be reliable on some servers.
  - `method`: Either `GET` or `POST`.
  - `domain` or `host`: The hostname requested, e.g. `www.example.com`.
  - `uri` or `url`: The local address requested, e.g. `/dirname/filename.php`
  - `time`: The UNIX timestamp of the request, usually the current timestamp.
  - `ip`: The IP address of the client.
  - `user_agent`: The user-agent string of the client.
  - `referer`: The referer, if any.
  - `ajax`: TRUE if the request seems to have been made by JavaScript, FALSE otherwise.
  - `keepalive`: The number of seconds if keepalive is enabled and the number of seconds is known,
    TRUE if keepalive is enabled but the number of seconds is not known,
    FALSE if keepalive is disabled.
  - `old_browser`: TRUE if the user-agent is Internet Explorer 7 or earlier, FALSE otherwise.
