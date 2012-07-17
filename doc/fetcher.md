
Fetcher Class
-------------

This class provides a shortcut for downloading files from the Web using cURL.

### Fetcher::download()

Arguments:

  - string $url
  - int $timeout (optional)
  - string $user_agent (optional)
  
Explanation:

  - This method downloads the file located at $url, and returns a string that contains the contents of the file.
  - This method _does not_ handle any of the strange conditions that you may encounter, such as redirects and 404 errors.
  - The default timeout is 30 seconds.
  - The default user agent is `PHP`.
  - If the cURL extension is not installed, `FetcherException` will be thrown.
