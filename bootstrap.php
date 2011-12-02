<?php

require __DIR__.'/vendors/Symfony/Component/ClassLoader/UniversalClassLoader.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;

// setup the autoloader
$loader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespace('Symfony', __DIR__.'/vendors');
$loader->registerNamespace('lithium', __DIR__.'/vendors');
$loader->registerNamespace('Zend', __DIR__.'/vendors/zf2/library');

$loader->register();

// manually require the Pimple file
require __DIR__.'/vendors/Pimple/lib/Pimple.php';