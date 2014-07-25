<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$file = __DIR__.'/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$autoload = require_once $file;

AnnotationRegistry::registerLoader(function ($class) {
    if (strpos($class, 'ActiveLAMP\\Bundle\\UtilsBundle') === 0) {
        $path = str_replace('ActiveLAMP\\Bundle\\UtilsBundle\\', '', $class);
        $path = __DIR__ . '/../' . str_replace('\\', DIRECTORY_SEPARATOR, $path) . '.php';
        var_dump(realpath($path));
        require_once $path;
    }

    return class_exists($class, false);
});