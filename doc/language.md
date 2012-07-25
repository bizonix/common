
Language Class
--------------

This class provides an easy way to retrieve translations of various strings into multiple languages.
If your website is multilingual, you should use the Language class rather than hard-coding strings into your code.

This class has one static method for configuration.
All other functionality can be accessed through instances of this class.

### Language::set_dir()

Arguments:

  - string $dir
  
Explanation:

  - Use this static method to set the default directory from which translation files will be loaded.
  - If all your translation files are stored in a single directory,
    it is strongly recommended that you use this method near the beginning of the script
    so that future calls to the Language class do not need to mention the directory.
  - However, it is also possible to override this setting on a per-instance basis.
    See the documentation for the constructor for more information on this.

### Constructor

Arguments:

  - string $language
  - string $dir_override (optional)

Explanation:

  - You must specify the name of the language when you create instances of the Language class.
    The name can be anything, as long as it matches the name of the file that contains the translations.
  - If you specify a directory name as the second argument, the instance will disregard the default directory
    and look for translations in the new directory.
    
Usage:

    \Common\Language::set_dir('/path/to/translations');
    $lang = new \Common\Language('fr');
    echo $lang->translate('hello');  // Bonjour

### Format for Translation Files

You should use one file for each language.
The filename should be `<lang>.php`, where `<lang>` is the _lower-case_ name of the language that you use in the constructor.
For example, if you want to use `fr` to refer to your French translations, the filename should be `fr.php`.

The file should define one associative array, named `$translations`.
Each key should be a short name for what you want translated,
and the corresponding value should be the full string in the respective language.

For example:

    <?php
    
    $translations = array(
        'hello' => 'Bonjour',
        'bye' => 'Au revoir',
        'my_name_is' => 'Je m'appelle %s',
        'loves' => '%s aime $s',
    );

### ->translate()

Arguments:

  - string $key
  - mixed $args (optional)
  
Explanation:

  - This method returns a translated string if a translation exists for `$key`, and NULL otherwise.
  - You can also pass an array of strings to be interpolated into the translation.
    If you do so, the translation should contain placeholders in the format used by `printf()`.
  - It is also possible to pass an arbitrary number of additional arguments instead of an array.

Usage:
    
    $lang = new \Common\Language('fr');
    echo $lang->translate('bye');  // Au revoir
    echo $lang->translate('my_name_is', array('Barack'));  // Je m'appelle Barack
    echo $lang->translate('loves', array('Barack', 'Michelle'));  // Barack aime Michelle
    echo $lang->translate('loves', 'Barack', 'Michelle');  // Same as above

### Shortcuts

Typing `translate()` every time you want to retrieve a translation can get rather tedious.
So the Language class offers two convenient shortcuts.

  - If there are no additional strings to interpolate into a translation,
    you can retrieve the translation simply by treating the key as a property.
  - If there are additional strings, you can treat the key as a method.

For example, the following two lines are equivalent:

    echo $lang->translate('bye');
    echo $lang->bye;

The following two lines are also equivalent:

    echo $lang->translate('loves', 'Barack', 'Michelle');
    echo $lang->loves('Barack', 'Michelle');
