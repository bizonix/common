
Timer Class
-----------

This class provides an easy way to measure elapsed time between any two moments during the execution of a PHP script.

### Timer::start()

Arguments:

  - string $name (optional)

Explanation:

  - Call this method to start a timer.
  - This method returns the current timestamp as a float.
  - If you specify a name for the timer, you can use the name to stop it later.
    This is useful if you have more than one timer running at the same time.

Usage:

    Timer::start();
    Timer::start('my_timer');

### Timer::stop()

Arguments:

  - string $name (optional)

Explanation:

  - Call this method to stop a timer.
  - This method returns the number of seconds elapsed since the timer started, as a float.
  - If you specify a name, the named timer will be stopped.
    If no timer with the given name is running, this method will return NULL.
  - If you do not specify a name, the most recently started timer will be stopped.
    If no timer is currently running, this method will return NULL.
  - The same timer cannot be stopped twice.

Usage:

    Timer::start('my_timer');
    echo Timer::stop('my_timer');  // 0.01176345

    Timer::start();  // Start 1st timer
    Timer::start();  // Start 2nd timer
    Timer::start();  // Start 3rd timer
    echo Timer::stop();  // Stop 3rd timer
    echo Timer::stop();  // Stop 2nd timer
    echo Timer::stop();  // Stop 1st timer

### Timer::stop_format()

Arguments:

  - string $name (optional)

Explanation:

  - This method works the same way as `stop()`, except it returns a formatted string.
  - The string will contain the number of milliseconds elapsed, with one digit after the decimal point,
    followed by the letters `ms`. For example: `125.8ms`.

