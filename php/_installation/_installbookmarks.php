<?php

/**
 * This is the installation file for the 0-one-file version of the php-login script.
 * It simply creates a new and empty database.
 */

// error reporting config
error_reporting(E_ALL);

// config
$db_type = "sqlite";
$db_sqlite_path = "../../bookmarks.db";

// create new database file / connection (the file will be automatically created the first time a connection is made up)
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// create new empty table inside the database (if table does not already exist)
$sql = 'CREATE TABLE IF NOT EXISTS `bookmarks` (
        `bookmark_id` INTEGER PRIMARY KEY,
        `user_id` INTEGER,
        `bookmark_title` varchar(256),
        `bookmark_url` varchar(256),
        `bookmark_date` varchar(64)
        );
        CREATE UNIQUE INDEX `user_id_UNIQUE` ON `bookmarks` (`user_id` ASC);
        CREATE UNIQUE INDEX `bookmark_id_UNIQUE` ON `bookmarks` (`bookmark_id` ASC);
        ';

// execute the above query
$query = $db_connection->prepare($sql);
$query->execute();

$sql = 'CREATE TABLE IF NOT EXISTS `tags` (
        `tag_id` INTEGER PRIMARY KEY,
       `tag_name` VARCHAR(16)
       )';
$query = $db_connection->prepare($sql);
$query->execute();
//Create joined table bookmark_tags
$sql = 'CREATE TABLE IF NOT EXISTS `bookmark_tags`(
       `bookmark_id` INTEGER NOT NULL,
       `tag_id` INTEGER NOT NULL,
       PRIMARY KEY(`bookmark_id`, `tag_id`)
       )';
$query = $db_connection->prepare($sql);
$query->execute();

// check for success
if (file_exists($db_sqlite_path)) {
    echo "Database $db_sqlite_path was created, installation was successful.";
} else {
    echo "Database $db_sqlite_path was not created, installation was NOT successful. Missing folder write rights ?";
}
