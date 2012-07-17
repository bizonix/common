<?php

class Template
{
    // Some instance variables.
    
    protected static $_dir = null;
    protected static $_scratch_dir = null;
    protected $_filename = null;
    protected $_language = null;
    protected $_vars = array();
    
    // Set the directory for template files.
    
    public static function set_dir($dir)
    {
        self::$_dir = rtrim($dir, '/');
    }
    
    // Set the directory for scratch space.
    
    public static function set_scratch_dir($dir)
    {
        self::$_scratch_dir = rtrim($dir, '/');
    }
    
    // Set the language object.
    
    public function set_language($language)
    {
        $this->_language = $language;
    }
    
    // Constructor.
    
    public function __construct($name)
    {
        // Check if the view file exists.
        
        $this->_filename = self::$_dir . '/' . $name . '.html';
        if (!file_exists($this->_filename)) throw new CommonTemplateException("Template '{$name}' does not exist.");
    }
    
    // Generic getter method.
    
    public function __get($name)
    {
        if (!isset($this->_vars[$name])) throw new CommonTemplateException("Undefined property: '{$name}'");
        return $this->_vars[$name];
    }
    
    // Generic setter method.
    
    public function __set($name, $value)
    {
        $this->_vars[$name] = $value;
    }
    
    // Render method.
    
    public function render($content_type = 'text/html')
    {
        // If returning, render the view in the output buffer.
        
        if ($content_type === false)
        {
            ob_start();
            extract($this->_vars);
            include $this->_get_compiled_filename($this->_filename);
            echo "\n";
            return ob_get_clean();
        }
        
        // Set some headers. Sorry, these values are hard-coded into the library.
        
        header('Content-Type: ' . $content_type . '; charset=UTF-8');
        header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
        header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
        header('Pragma: no-cache');
        
        // Load the view, transform it, display it, and exit.
        
        extract($this->_vars);
        include $this->_get_compiled_filename($this->_filename);
        echo "\n";
        exit();
    }
    
    // Get the filename of the compiled template.
    
    protected function _get_compiled_filename($filename)
    {
        // If given a relative path, convert to absolute.
        
        if ($filename[0] !== '/') $filename = self::$_dir . '/' . $filename . '.html';
        
        // If the template does not exist, throw an exception.
        
        if (!file_exists($filename)) throw new CommonTemplateException("Template '{$filename}' does not exist.");
        
        // If this template has already been compiled, use the compiled version.
        
        $compiled_location = self::$_scratch_dir . '/' . md5($filename) . '~' . basename($filename);
        if (file_exists($compiled_location) && filemtime($compiled_location) > filemtime($filename))
        {
            return $compiled_location;
        }
        
        // Load up the template, and compile it.
        
        $template_original = file_get_contents($filename);
        $template_compiled = preg_replace_callback('/(?:\\{|<!--(?=[@#]))(\\S[^\\}]+)(?:\\}|-->)/U', array('Template', '_compile'), $template_original);
        
        // Save the parsed view for future reference.
        
        file_put_contents($compiled_location, $template_compiled, LOCK_EX);
        return $compiled_location;
    }
    
    // Template compiler.
    
    protected static function _compile($matches)
    {
        // Get the code, dropping any semicolons at the end.
        
        $code = trim($matches[1], ' ;');
        if ($code === '') return '';
        
        // If the code begins with '@', replace with literal PHP code.
        
        if ($code[0] === '@')
        {
            return '<?php ' . substr($code, 1) . '; ?>';
        }
        
        // If the code begins with '#', replace with control structures.
        
        if ($code[0] === '#')
        {
            // Template inclusion.
            
            if (!strncmp($code, '#include', 8))
            {
                if ($code[9] === '$')
                {
                    return '<?php include $this->_get_compiled_filename(' . substr($code, 9) . '); ?>';
                }
                else
                {
                    return '<?php include $this->_get_compiled_filename(\'' . substr($code, 9) . '\'); ?>';
                }
            }
            
            // Endfor/endforeach/endif/endwhile.
            
            if (!strncmp($code, '#end', 4)) return '<?php ' . substr($code, 1) . '; ?>';
            
            // Everything else: for/foreach/if/while/else/elseif.
            
            return '<?php ' . rtrim(substr($code, 1), ':') . ': ?>';
        }
        
        // If the code begins with ':', replace with a language translation.
        
        if ($code[0] === ':')
        {
            // If it's a simple key, just call translate() on the language object.
            
            $argpos = strpos($code, '(');
            if ($argpos === false)
            {
                return '<?php echo $this->_language->translate(\'' . substr($code, 1) . '\'); ?>';
            }
            
            // Otherwise, call translate() with the list of arguments.
            
            else
            {
                $key = substr($code, 1, $argpos - 1);
                $args = substr($code, $argpos);
                return '<?php echo $this->_language->translate(\'' . $key . '\', array' . $args . '); ?>';
            }
        }
        
        // Otherwise, we have a print.
        
        else
        {
            // Look for pipes.
            
            $pipes = explode('|', $code);
            
            // If there's only one part, just print it.
            
            if (count($pipes) === 1) return '<?php echo htmlspecialchars(' . $code . ', ENT_QUOTES, \'UTF-8\', true); ?>';
            
            // Otherwise, initialize the filter chain.
            
            $escape = true;
            $chain = trim($pipes[0]);
            array_shift($pipes);
            
            // Loop over other elements of the chain.
            
            foreach ($pipes as $pipe)
            {
                // Separate any argument from the filter name.
                
                $pipe = trim($pipe);
                $argpos = strpos($pipe, ':');
                if ($argpos !== false)
                {
                    $argname = strtolower(substr($pipe, 0, $argpos));
                    $argvalue = substr($pipe, $argpos + 1);
                }
                else
                {
                    $argname = strtolower($pipe);
                    $argvalue = '';
                }
                
                // Apply filters.
                
                switch ($argname)
                {
                    case 'noescape':
                        $escape = false;
                        break;
                        
                    case 'strip':
                        $chain = 'htmlspecialchars(strip_tags(' . $chain . '), ENT_QUOTES, \'UTF-8\', true)';
                        break;
                        
                    case 'urlencode':
                        $chain = 'urlencode(' . $chain . ')';
                        break;
                        
                    case 'lower':
                        $chain = 'strtolower(' . $chain . ')';
                        break;
                        
                    case 'upper':
                        $chain = 'strtoupper(' . $chain . ')';
                        break;
                        
                    case 'br':
                        $chain = $escape ? ('nl2br(htmlspecialchars(' . $chain . ', ENT_QUOTES, \'UTF-8\', true))') : ('nl2br(' . $chain . ')');
                        $escape = false;
                        break;
                        
                    case 'thousands':
                        $chain = 'number_format(' . $chain . (empty($argvalue) ? '' : (', ' . $argvalue)) . ')';
                        break;
                        
                    case 'length':
                        $chain = 'strlen(' . $chain . ')';
                        break;
                        
                    case 'chars':
                        $chain = 'mb_strlen(' . $chain . ', \'UTF-8\')';
                        break;
                        
                    case 'date':
                        $chain = 'date(' . $argvalue . ', ' . $chain . ')';
                        break;
                        
                    case 'default':
                        $chain = '(' . $chain . ' === false || ' . $chain . ' === null) ? ' . $argvalue . ' : ' . $chain;
                        break;
                    
                    case 'link':
                        $argvalue = htmlspecialchars($argvalue, ENT_QUOTES, 'UTF-8', false);
                        $chain = $escape ? ('htmlspecialchars(' . $chain . ', ENT_QUOTES, \'UTF-8\', true)') :  $chain;
                        $chain = '\'<a href="\'.' . (empty($argvalue) ? $chain : $argvalue) . '.\'">\'.' . $chain . '.\'</a>\'';
                        $escape = false;
                        break;
                }
            }
            
            // Return the result.
            
            return $escape ? ('<?php echo htmlspecialchars(' . $chain . ', ENT_QUOTES, \'UTF-8\', true); ?>') : ('<?php echo ' . $chain . '; ?>');
        }
        
        // In all other cases, return intact.
        
        return $matches[0];
    }
}

class CommonTemplateException extends CommonException { }
