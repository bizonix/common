
Template Class
--------------

This class allows you to write and load templates using a custom syntax as described below.
It may be helpful if you are tired of constantly calling `echo`, `escape`, etc. in your views.

### Template::set_dir()

Arguments:

  - string $dir

Explanation:

  - Call this method as part of your configuration routine, before creating any instances of the Template class.
  - The directory should be where your templates are located.

### Template::set_scratch_dir()

Arguments:

  - string $dir

Explanation:

  - Call this method as part of your configuration routine, before creating any instances of the Template class.
  - The directory should be writable. It is where compiled templates will be stored.

### Constructor

Arguments:

  - string $name

Explanation:

  - Supply the name of the template that you want to load, without the extension.
  - Templates should have an `.html` extension.
  - If the file cannot be found, `ViewException` will be thrown.

### Properties

The Template class provides a generic getter and setter for accessing properties.
See the example below for how they are used.

### ->add_language()

Arguments:

  - object $language

Explanation:

  - Call this method to add instances of the Language class to the template.
    Translations can then be accessed by the template using the syntax described below.
  - You can add multiple languages. The template class will search for translations in the same order that you added languages.
    This can be useful if some of your translations are incomplete and you would like to provide a default fallback language.

### ->translate()

Arguments:

  - string $key
  - array $args (optional)

Explanation:

  - Call this method from inside your templates (or use a shortcut as described below) to access translations.
  - If your translation contains placeholders, you can also pass parameters as an array.

### render()

Arguments:

  - mixed $return_or_content_type (optional)

Explanation:
  
  - This method compiles the template, executes it, sends the output to the client, and terminates the current request.
  - The default content type is `text/html`. You can supply a different content type if you want.
  - If you supply `false` as the only argument, the output will be returned, and the request will not be terminated.
    Use this option if you would like to process the output further.
  - Templates are converted into PHP scripts and cached in the scratch directory.
    The Template class will use the file modification time to detect when templates have changed, and recompile them when necessary.

### Template Syntax

This syntax is intended to make it just a little bit easier for you to write templates compared to plain PHP.
At the very least, it helps you avoid writing `<?php echo ...` all the time.
It is not meant to be a full-blown template engine like some others.
For all intents and purposes, templates are treated as PHP scripts that are include()'d when necessary.
Don't do anything in the template files that you wouldn't do in a regular PHP script!

If you have a syntax error in a template, the error will appear to be in the compiled file, located in the scratch directory.
But the last part of the filename and the line number will be the same, so that you can track down the error and fix it.

#### Variables

  - `{$var}` is replaced by the value of the variable `$var`.
    Any HTML tags or special characters are automatically escaped, unless the `noescape` filter is used (see below).
  - `{$var|filter1|filter2|...}` : You can apply simple transformations to variables by appending "filters" separated by `|`.
    Filters will be evaluated in the order in which they are specified.

#### Control Structures

  - `{#include filename}` includes the contents of another template at the current position.
    You can also specify a template located in a subdirectory, e.g. `{#include subdir/filename}`.

  - `{#include $var}` allows you to use the value of `$var` instead of the actual filename.
    This can be useful but also very dangerous; do not use any user-supplied values for this purpose.

  - Conditions and Loops
    - `{#if ( condition )} ... {#elseif} ... {#else} ... {#endif}`
    - `{#for ( $i = 0; $i < $max; $i++ )} ... {#endfor}`
    - `{#foreach ( $array as $key => $value )} ... {#endforeach}`
    - `{#while ( condition )} ... {#endwhile}`

  - Alternate Syntax : conditions and loops can also be put inside HTML comment tags, e.g. `<!--#if()--> ... <!--#endif-->`.
    Some people think this looks better. Others may like it because it makes control structures look different from plain variables.

#### Translation

  - `{:key}` will be replaced with the translation for `key`, using the current template's language and translation set.
  - `{:key($arg1, $arg2)}` can be used for translations with placeholders.

#### Code Insertion

  - `{@code}` will be transformed into `<?php code ?>`. Any valid PHP code can be inserted here.
    This can be useful if you need to do some quick variable juggling in the middle of a loop.
    Note that this functionality can become a securiry risk if not carefully used.
    Also, this construct makes it more tempting to mix application code with presentation, which is usually considered bad practice.
    _Use with caution._
    
#### Filters

  - `noescape` : mark this variable as safe (do not escape).
  - `strip` : strip all HTML tags from this variable.
  - `urlencode` : URL encode this variable.
  - `lower` : convert this variable to lower-case.
  - `upper` : convert this variable to upper-case.
  - `br` : convert line breaks into `<br />` tags.
  - `thousands` or `thousands:decimals` : format the value of this variable with commas every three digits.
    You can also specify the number of decimal digits after the dot; if not specified, no decimal digits will be shown.
  - `neatsize` : format the value of this variable for file size display.
  - `length` : display the length of the string in bytes, instead of the string itself.
  - `chars` : display the number of UTF-8 characters in the string, instead of the string itself.
  - `date:'format'` : format the value of this variable using the standard date formatting notation.
  - `default:'value'` : if the variable is false or null, replace it with the default value.
  - `link:'url'` : turn this string into a hyperlink, linking to `url`.

#### Caution

  - There should be no space between the opening brace (`{`) and the beginning of your variable, translation name, or control structure.
    If there is any space after the opening brace, it will be skipped when the template is compiled.
    This is to prevent CSS (inside `<style>` tags) and inline JavaScript from being misinterpreted by the template parser.
    Obviously, any CSS or JavaScript that is included in the template should have at least one space (or a newline) after each opening brace.
  - Auto-escaping is automatically disabled when you use the `br` or `link` filter, because auto-escape would break the generated HTML.
    But even when this happens, `br` and `link` are only applied _after_ the variable itself is escaped.
  - String arguments must be enclosed in quotes.

#### Examples

    {@$var = 'Hello World!'}
    {$var}                                        // Hello World!
    {$var|lower}                                  // hello world!
    {$var|length}                                 // 12
    {$var|link:'http://www.google.com/'}          // <a href="http://www.google.com/">Hello World!</a>
    
    {:hello}                                      // Bonjour (assuming 'hello' => 'Bonjour')
    {:hello('Pierre')}                            // Bonjour, Pierre! (assuming 'hello' => 'Bonjour, %s!')
    
    {@$var = array(1234, 56789, false)}
    {#foreach($var as $x)}
        {$x|default:0|thousands} /                // 1,234 / 56,789 / 0
    {#endforeach}
    
    {@$var = "Hello <b>Frigging</b> World! \n O HAI"}
    {$var}                                        // Hello &lt;b&gt;Frigging&lt;/b&gt; World! \n O HAI
    {$var|strip|br}                               // Hello Frigging World! <br />\n O HAI
