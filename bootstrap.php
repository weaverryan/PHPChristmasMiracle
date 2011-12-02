<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

// setup the autoloader
$loader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespace('Symfony', __DIR__.'/vendors');
$loader->register();