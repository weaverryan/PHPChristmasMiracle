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

DROP TABLE IF EXISTS php_camp;

CREATE TABLE php_camp (
    id INTEGER PRIMARY KEY,
    attendee TEXT,
    avatar_url TEXT
);

INSERT INTO php_camp VALUES(1,'weierophinney', 'http://i.vimeocdn.com/portrait/4175704_300x300.jpg');
INSERT INTO php_camp VALUES(2,'fabpot','https://pbs.twimg.com/profile_images/443336758403424256/U5bzXI5l_400x400.jpeg');
INSERT INTO php_camp VALUES(3, 'pmjones', 'https://pbs.twimg.com/profile_images/482556486824910848/Bb4fyXhn_400x400.jpeg');
INSERT INTO php_camp VALUES(4, 'jmikola', '/images/wurstcon.jpg');

EOF
    ;

$dbh->exec($query);
$dbh->commit();