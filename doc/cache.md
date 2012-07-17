
Cache Class
-----------

This class offers a very easy way to use Memcached with your web application.
It is compatible with both the new `Memcached` extension and the old `Memcache` extension.
Since this class is static, it also helps you access Memcached from anywhere in your project
without having to pass around connection handles or relying in global variables.

### Cache::initialize()

Arguments:

  - array $servers

Explanation:

  - The Cache class needs to be initialized before any other methods can be called.
    Once it is initialized, it can be referenced from anywhere in your project.
  - Each element of the array should be in the format `ipaddress[:port[:weight]]`.
    The port defaults to 11211 if not specified.
    Weights do not need to add up to any particular sum, but default to 100 if not specified.
  - Examples of valid inputs include:

    array('127.0.0.1')
    array('127.0.0.1:11211', '127.0.0.1:11212')
    array('192.168.0.101:11211:50', '192.168.0.102:11211:30', ''192.168.0.103:11211:20')

Errors:

  - PHP 5.3: `\Common\CacheException` if no compatible extension is found.
  - PHP 5.2: `CommonCacheException` if no compatible extension is found.

### Cache::get()

Arguments:

  - string $key

Explanation:

  - This method returns the value stored at the specified key.
  - If the key is not found, `FALSE` will be returned.

### Cache::set()

Arguments:

  - string $key
  - mixed $value
  - int $ttl (optional)

Explanation:

  - This method stores the value under the specified key.
  - This method returns `TRUE` on success and `FALSE` on failure.
  - If `$ttl` is not specified, it defaults to 3600 seconds.
  
### Cache::delete()

Arguments:

  - string $key

Explanation:

  - This method deletes the specified key.
  - This method returns `TRUE` on success and `FALSE` on failure.
  
### Cache::callback()

Arguments:

  - callback $callback
  - array $args (optional)
  - int $ttl (optional)
  
Explanation:

  - This method calls the specified callback (function or static method) with the provided arguments,
    stores the result in the cache, and returns the same result.
  - When the same method is called _with the same arguments_, the result is fetched from cache,
    and the callback is not called again until the cache expires.
  - This can be useful if you have a function that uses a lot of resources.
    Instead of rewriting the function to support caching, you can just wrap it in this method for a quick performance gain.
  - Currently, the callback cannot be a closure or anonymous function.
    It must be an actual function or static method, because the name of the callback is used as part of the auto-generated cache key.
  - If `$ttl` is not specified, it defaults to 300 seconds.

