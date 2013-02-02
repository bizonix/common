
View Class
----------

This class allows interaction with view scripts (regular PHP files that contain mostly HTML).
If you would like to use more elaborate templates, please use the Template class instead.

### View::set_dir()

Arguments:

  - string $dir

Explanation:

  - Call this method as part of your configuration routine, before creating any instances of the View class.
  - The directory should be where your view scripts are located.

### Constructor

Arguments:

  - string $name

Explanation:

  - Supply the name of the PHP file that you want to load, without the extension.
  - View scripts should have a `.php` extension.
  - If the file cannot be found, `ViewException` will be thrown.

### Properties

The View class provides a generic getter and setter for accessing properties.
See the example below for how they are used.

### ->render()

Arguments:

  - mixed $return_or_content_type (optional)

Explanation:
  
  - This method executes the view script, sends the output to the client, and terminates the current request.
  - The default content type is `text/html`. You can supply a different content type if you want.
  - If you supply `false` as the only argument, the output will be returned, and the request will not be terminated.
    Use this option if you would like to process the output further.

### Example

example.php

    <html>
    <head>
        <title><?php echo \Common\Security::filter($title, 'escape'); ?></title>
    </head>
    <body>
        <h1>Hello World!</h1>
        <p>Your name is <?php echo \Common\Security::filter($name, 'escape'); ?>.</p>
        <p>Your occupation is <?php echo \Common\Security::filter($occupation, 'escape'); ?>.</p>
        <p>Your children are:</p>
        <ul>
            <?php foreach ($children as $child): ?>
                <li><?php echo \Common\Security::filter($child, 'escape'); ?></li>
            <?php endforeach; ?>
        </ul>
    </body>
    </html>

controller.php

    $view = new View('example');
    $view->title = 'User Profile';
    $view->name = 'Elizabeth II';
    $view->occupation = 'Queen';
    $view->children = array('Charles', 'Anne', 'Andrew', 'Edward');
    $view->render();
