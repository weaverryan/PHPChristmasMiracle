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

/**
 * *************** Our one-method framework
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use lithium\net\http\Router;
use lithium\action\Request as Li3Request;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

function _run_application(Pimple $c)
{
    // execute our routing
    $c['router']->parse($c['li3_request']);

    // merge the matched attributes back into Symfony's request
    $c['request']->attributes->add($c['li3_request']->params);

    // get the "controller" out, or default to error404
    $controller = $c['request']->attributes->get('controller', 'error404');

    if ($controller == 'error404') {
        $msg = sprintf('Controller not found for "%s"', $c['request']->getPathInfo());
        $c['logger']->log($msg, Logger::ERR);
    } else {
        $c['logger']->log(sprintf('Found controller "%s"', $controller), Logger::INFO);
    }

    // execute the controller and get the response
    $response = call_user_func_array($controller, array($c['request'], $c));
    if (!$response instanceof Response) {
        throw new Exception(sprintf('Your controller "%s" did not return a response!!', $controller));
    }

    return $response;
}

/**
 * *************** Container Setup
 */

$c = new Pimple();

// configuration
$c['connection_string'] = 'sqlite:'.__DIR__.'/data/database.sqlite';
$c['log_path'] = __DIR__.'/data/web.log';

// Service setup
$c['connection'] = $c->share(function(Pimple $c) {
    return new PDO($c['connection_string']);
});

$c['request'] = $c->share(function() {
    return Request::createFromGlobals();
});

$c['li3_request'] = $c->share(function(Pimple $c) {
    $li3Request = new Li3Request();
    $li3Request->url = $c['request']->getPathInfo();

    return $li3Request;
});

$c['router'] = $c->share(function() {
    $router = new Router();

    return $router;
});

$c['logger_writer'] = $c->share(function(Pimple $pimple) {
    return new Stream($pimple['log_path']);
});
$c['logger'] = $c->share(function(Pimple $pimple) {
    return new Logger($pimple['logger_writer']);
});

return $c;