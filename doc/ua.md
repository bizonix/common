
UA Class
--------

This class offers a couple of static methods that you can use to figure out the type of the user agent.

### UA::is_mobile()

Arguments: none.

Explanation:

  - This method returns `true` if the user agent seems to be a mobile browser, and `false` otherwise.
  - When unsure, this method returns `false`. This may result in a small number of false negatives.

Usage:

    if (UA::is_mobile()) {
        // Redirect to mobile site
    } else {
        // Display regular site
    }

### UA::is_robot()

Arguments: none.

Explanation:

  - This method returns `true` if the user agent seems to be a robot, and `false` otherwise.
  - Most common crawlers, such as Gooblebot and Facebook Scraper, are recognized.
    But there are many different types of robots out there, so please don't rely on this method too much!
  - When unsure, this method returns `false`.
