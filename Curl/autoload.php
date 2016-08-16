<?php
/**
 * Autoloader.
 *
 * @category PHP
 * @author   Arno <arnoliu@tencent.com>
 */

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    throw new Exception('The Curl SDK requires PHP version 5.3 or higher.');
}

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

/**
 * Register the autoloader.
 *
 * @param string $class class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefix = 'Curl\\';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Another namespace.
        return;
    }

    $class_name = substr($class, $len);

    $file = rtrim(__DIR__, DS) . DS . strtr($class_name, '\\', DS) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

//end of script
