<?php

try {
    $dbPath = __DIR__.'/data/database.sqlite';
    $dbh = new PDO('sqlite:'.$dbPath);
} catch(PDOException $e) {
    die('Panic! '.$e->getMessage());
}

$uri = $_SERVER['REQUEST_URI'];
if ($pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

if ($uri == '/' || $uri == '') {

    echo '<h1>PHP Camp!</h1>';
    echo '<a href="/attendees">See the attendees</a>';
    if (isset($_GET['name'])) {
        echo sprintf('<p>Oh, and hello %s!</p>', $_GET['name']);
    }

} elseif ($uri == '/attendees') {

    $sql = 'SELECT * FROM php_camp';
    echo '<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />';
    echo '<h1>PHP Camp Attendees</h1>';
    echo '<table class="table" style="width: 300px;">';
    foreach ($dbh->query($sql) as $row) {
        echo sprintf(
            '<tr><td style="font-size: 24px;">%s</td><td><img src="%s" height="120" /></td></tr>',
            $row['attendee'],
            $row['avatar_url']
        );
    }
    echo '</table>';

} else {
    header("HTTP/1.1 404 Not Found");
    echo '<h1>404 Page not Found</h1>';
    echo '<p>Find a boy (or girl) scout - they can fix this!</p>';
}
