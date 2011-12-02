<?php

/*
 * Define our routes
 */

$c['router']->connect('/letters', array('controller' => 'letters'));
$c['router']->connect('/{:name}', array('controller' => 'homepage', 'name' => null));