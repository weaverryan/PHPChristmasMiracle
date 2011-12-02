<?php
require 'bootstrap.php';

try {
    $dbPath = __DIR__.'/data/database.sqlite';
    $dbh = new PDO('sqlite:'.$dbPath);
} catch(PDOException $e) {
    die('Panic! '.$e->getMessage());
}

// create a request object to help us
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use lithium\net\http\Router;
use lithium\action\Request as Li3Request;

$request = Request::createFromGlobals();

// create a lithium request from a Symfony request, since the interfaces aren't compatible
$li3Request = new Li3Request();
$li3Request->url = $request->getPathInfo();

// create a router, build the routes, and then execute it
$router = new \lithium\net\http\Router();
$router->connect('/', array('controller' => 'homepage'));
$router->connect('/letters', array('controller' => 'letters'));
$li3Request = $router->parse($li3Request);

// grab the controller from the results
if ($li3Request && isset($li3Request->params['controller'])) {
    $controller = $li3Request->params['controller'];
} else {
    $controller = 'error404';
}

// execute the controller and get the response
$response = call_user_func_array($controller, array($request));
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
    if ($name = $request->query->get('name')) {
        $content .= sprintf('<p>Oh, and hello %s!</p>', $name);
    }

    return new Response($content);
}

function letters(Request $request)
{
    global $dbh;

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
