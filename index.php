<?php
require 'bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use lithium\net\http\Router;
use lithium\action\Request as Li3Request;

$pimple = new Pimple();

// configuration
$pimple['connection_string'] = 'sqlite:'.__DIR__.'/data/database.sqlite';

// Service setup
$pimple['connection'] = $pimple->share(function(Pimple $pimple) {
    return new PDO($pimple['connection_string']);
});

$pimple['request'] = $pimple->share(function() {
    return Request::createFromGlobals();
});

$pimple['li3_request'] = $pimple->share(function(Pimple $pimple) {
    $li3Request = new Li3Request();
    $li3Request->url = $pimple['request']->getPathInfo();

    return $li3Request;
});

$pimple['router'] = $pimple->share(function() {
    $router = new Router();

    // add some routes
    $router->connect('/letters', array('controller' => 'letters'));
    $router->connect('/{:name}', array('controller' => 'homepage', 'name' => null));

    return $router;
});

// execute our routing
$result = $pimple['router']->parse($pimple['li3_request']);

// merge the matched attributes back into Symfony's request
$pimple['request']->attributes->add($pimple['li3_request']->params);

// get the "controller" out, or default to error404
$controller = $pimple['request']->attributes->get('controller', 'error404');

// execute the controller and get the response
$response = call_user_func_array($controller, array($pimple['request'], $pimple));
if (!$response instanceof Response) {
    throw new Exception(sprintf('Your controller "%s" did not return a response!!', $controller));
}

$response->send();

/**
 * My Controllers!!!!
 */

function homepage(Request $request) {
    $content = '<h1>Welcome to PHP Santa</h1>';
    $content .= sprintf('<a href="/letters">Read the letters</a>');
    if ($name = $request->attributes->get('name')) {
        $content .= sprintf('<p>Oh, and hello %s!</p>', $name);
    }

    return new Response($content);
}

function letters(Request $request, Pimple $pimple)
{
    $dbh = $pimple['connection'];

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
