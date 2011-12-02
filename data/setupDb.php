<?php

try {
    $dbPath = __DIR__.'/database.sqlite';
    $dbh = new PDO('sqlite:'.$dbPath);
} catch(PDOException $e) {
    die('Panic! '.$e->getMessage());
}

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/*** begin the transaction ***/
$dbh->beginTransaction();

$query = <<<EOF

DROP TABLE IF EXISTS php_santa_letters;

CREATE TABLE php_santa_letters (
    id INTEGER PRIMARY KEY,
    content TEXT,
    received_at TIMESTAMP
);

INSERT INTO php_santa_letters VALUES(1,'A unified Request Response interface','2011-12-01');
INSERT INTO php_santa_letters VALUES(2,'A package manager that''s fun to use!','2011-12-01');
EOF
    ;

$dbh->exec($query);
$dbh->commit();