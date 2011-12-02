<?php
require 'bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use lithium\net\http\Router;
use lithium\action\Request as Li3Request;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Container Setup
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

    // add some routes
    $router->connect('/letters', array('controller' => 'letters'));
    $router->connect('/{:name}', array('controller' => 'homepage', 'name' => null));

    return $router;
});

$c['logger_writer'] = $c->share(function(Pimple $pimple) {
    return new Stream($pimple['log_path']);
});
$c['logger'] = $c->share(function(Pimple $pimple) {
    return new Logger($pimple['logger_writer']);
});

/**
 * Our Framework
 */

// execute our routing
$result = $c['router']->parse($c['li3_request']);

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

// send the response
$response->send();


/**
 * Our Application
 */

function homepage(Request $request) {
    $content = '<h1>Welcome to PHP Santa</h1>';
    $content .= sprintf('<a href="/letters">Read the letters</a>');
    if ($name = $request->attributes->get('name')) {
        $content .= sprintf('<p>Oh, and hello %s!</p>', $name);
    }

    return new Response($content);
}

function letters(Request $request, Pimple $c)
{
    $dbh = $c['connection'];

    $sql = 'SELECT * FROM php_santa_letters';
    $content = '<h1>Read the letters to PHP Santa</h1>';
    $content .= '<ul>';
    foreach ($dbh->query($sql) as $row) {
        $content .= sprintf('<li>%s - dated %s</li>', $row['content'], $row['received_at']);
    }
    $content .= '</ul>';

    return new Response($content);
}

function error404(Request $request)
{
    $content = '<h1>404 Page not Found</h1>';
    $content .= '<p>This is most certainly *not* an xmas miracle</p>';

    $response = new Response($content);
    $response->setStatusCode(404);

    return $response;
}
