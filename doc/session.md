
Session Class
--------------

This class provides a light wrapper around PHP's built-in session management functions.
It is one of the least useful classes in this collection, due to its simplicity.

### Session::start()

Arguments:

  - string $name (optional)
  - string $save_path (optional)

Explanation:

  - This method starts the session with the given name.
    This is equivalent to `session_start()`, except that the name can be customized in a single call.
  - This method also forces session IDs to be regenerated every 5 minutes.
  - If the name is not given, `PHPSESSID` is used by default.
  - You can also specify a directory where session files will be stored.
    By default, the configuration in php.ini is used.

### Session::refresh()

Arguments: none.

Explanation:

  - This method forces the session ID to be regenerated.

### Session::login()

Arguments:

  - int or string $id

Explanation:

  - This method stores the value of `$id` (integer or string) in the session.
    The stored value can be easily retrieved later by calling other methods.

### Session::logout()

Arguments: none.

Explanation:

  - This method destroys the session.

### Session::get_login_id()

Arguments: none.

Explanation:

  - This method returns the login ID, previously saved by calling `login()`.
  - If the user is not logged in, this method returns `null`.

### Session::get_logout_token()

Arguments: none.

Explanation:

  - When you call `login()`, a random token is generated and stored in the session.
    This can be used to prevent CSRF attacks that cause the user to be logged out.
  - This method returns the previously generated logout token, if it exists.
    If the user is not logged in, this method returns the empty string.

### Session::add_token()

Arguments:

  - string $token

Explanation:

  - This method stores the given token (usually a random string) in the session.
    The tokens can be checked later to prevent CSRF attacks.

### Session::check_token()

Arguments:

  - string $token

Explanation:

  - This method returns `true` if the given token has already been stored in the session, and `false` otherwise.

### Session::clear_tokens()

Arguments: none.

Explanation:

  - This method clears all stored tokens from the session.
