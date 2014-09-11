<?php

/**
 * This is a helper file that simply outputs the content of the users.db file.
 * Might be useful for your development.
 */
// USERS SECTION
// error reporting config
error_reporting(E_ALL);

// config
$db_type = "sqlite";
$db_sqlite_path = "../../users.db";

// create new database connection
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// query
$sql = 'SELECT * FROM users';

// execute query
$query = $db_connection->prepare($sql);
$query->execute();

// show all the data from the "users" table inside the database
var_dump($query->fetchAll());

///BOOKMARKS SECTION

$db_type = "sqlite";
$db_sqlite_path = "../../bookmarks.db";

// create new database connection
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// query
$sql = 'SELECT * FROM bookmarks';

// execute query
$query = $db_connection->prepare($sql);
$query->execute();

// show all the data from the "bookmarks" table inside the database
var_dump($query->fetchAll());

///TAG SECTION

$db_type = "sqlite";
$db_sqlite_path = "../../bookmarks.db";

// create new database connection
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// query
$sql = 'SELECT * FROM tags';

// execute query
$query = $db_connection->prepare($sql);
$query->execute();

// show all the data from the "tags" table inside the database
var_dump($query->fetchAll());


///TAG SECTION

$db_type = "sqlite";
$db_sqlite_path = "../../bookmarks.db";

// create new database connection
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// query
$sql = 'SELECT * FROM bookmark_tags';

// execute query
$query = $db_connection->prepare($sql);
$query->execute();

// show all the data from the "bookmark_tags" table inside the database
var_dump($query->fetchAll());

///TAG SECTION

$db_type = "sqlite";
$db_sqlite_path = "../../bookmarks.db";

// create new database connection
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// query


$sql = 'SELECT bookmarks.bookmark_id, tags.tag_id
        FROM bookmarks
        INNER JOIN bookmark_tags
        ON bookmark_tags.bookmark_id = bookmarks.bookmark_id
        INNER JOIN tags
        ON tags.tag_id = bookmark_tags.tag_id';

$query = $db_connection->prepare($sql);
$query->execute();

// show all the data from the "joined" table inside the database
var_dump($query->fetchAll());
