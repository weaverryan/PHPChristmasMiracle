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
$request = Request::createFromGlobals();

$uri = $request->getPathInfo();

if ($uri == '/' || $uri == '') {

    $content = '<h1>Welcome to PHP Santa</h1>';
    $content .= sprintf('<a href="/letters">Read the letters</a>');
    if ($name = $request->query->get('name')) {
        $content .= sprintf('<p>Oh, and hello %s!</p>', $_GET['name']);
    }

    $response = new Response($content);

} elseif ($uri == '/letters') {

    $sql = 'SELECT * FROM php_santa_letters';
    $content = '<h1>Read the letters to PHP Santa</h1>';
    $content .= '<ul>';
    foreach ($dbh->query($sql) as $row) {
        $content .= sprintf('<li>%s - dated %s</li>', $row['content'], $row['received_at']);
    }
    $content .= '</ul>';

    $response = new Response($content);

} else {
    $content = '<h1>404 Page not Found</h1>';
    $content .= '<p>This is most certainly *not* an xmas miracle</p>';

    $response = new Response($content);
    $response->setStatusCode(404);
}

$response->send();