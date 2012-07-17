<?php

include dirname(__FILE__) . '/exception.php';

function common_autoloader($class_name)
{
    $class_name = strtolower($class_name);
    $file_name = dirname(__FILE__) . '/' . $class_name . '.php';
    if (file_exists($file_name)) include $file_name;
}

spl_autoload_register('common_autoloader');
