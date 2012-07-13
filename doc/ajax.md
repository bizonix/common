
AJAX Class
----------

This class provides some shortcuts for quickly returning some message or content to the client.
This is particularly useful when handling AJAX requests, hence the name of this module.
This class normally assumes that the request is made using JavaScript,
and that the response will be processed by JavaScript.
The type of the response can be determined by reading the "status" property of the returned object.

### AJAX::content()

Arguments:

  - mixed $content

Explanation:

  - This method prints a JSON string formatted as described below, and exits.
  - If the provided content is not a string, it will be converted to a JSON representation.
  - If the request was _not_ made using JavaScript, this method simply prints $content and exits.

Usage:

    AJAX::content('This is the content.');
    
    { "status": "CONTENT", "content": "This is the content." }
    
    AJAX::content(array('apple', 'banana', 'cherry'));
    
    { "status": "content", "content": ["apple", "banana", "cherry"] }

### AJAX::redirect()

Arguments:

  - string $location
  
Explanation:

  - This method prints a JSON string formatted as described below, and exits.
    It is up to client-side JavaScript to process the response and trigger a redirect.
  - If the request was _not_ made using JavaScript, this method will cause an HTTP 302 redirect.
  
Usage:

    AJAX::redirect('https://github.com/');
    
    { "status": "REDIRECT", "location": "https://github.com/" }

### AJAX::error()

Arguments:

  - string $message
  
Explanation:

  - This method prints a JSON string formatted as described below, and exits.
  - If the request was _not_ made using JavaScript, this method will print `ERROR: `
    followed by the message. No special HTTP headers will be sent.
  
Usage:

    AJAX::error('Permission denied.');
    
    { "status": "ERROR", "message": "Permission denied." }

### JavaScript example (using jQuery)

    var form = $("#form");
    $.ajax({
        "url": form.attr("action"),
        "type": form.attr("method"),
        "data": form.serialize(),
        "success": function(data) {
            switch (data.status) {
                case "CONTENT":
                    /* Insert data.content into some element */
                    break;
                case "REDIRECT":
                    window.location = data.location;
                    break;
                case "ERROR":
                    alert(data.message);
                    break;
            }
        }
    });
