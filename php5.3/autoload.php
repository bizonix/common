<?php

spl_autoload_register(function($class_name)
{
    $class_name = ltrim(strtolower(str_replace('\\', '/', $class_name)), '/');
    if (!strncmp($class_name, 'common/', 7))
    {
        $file_name = __DIR__ . '/' . substr($class_name, 7) . '.php';
        if (file_exists($file_name)) include $file_name;
    }
});
