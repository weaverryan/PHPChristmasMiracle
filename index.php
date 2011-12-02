<?php

// create the pimple container, configure autoloader, include our "framework" function
$c = require 'bootstrap.php';
require 'routing.php';
require 'controllers.php';

$response = _run_application($c);
$response->send();