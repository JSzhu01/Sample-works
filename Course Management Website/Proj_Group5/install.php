<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "config.php";
require "modules/models/user.php";

// Load database tables
try {
    $connection = new PDO("mysql:host=$host", $username, $password, $options);
    $sql = file_get_contents("data/init.sql");
    $sql = sprintf($sql, $dbname);
    $connection->exec($sql);

    echo "Database and table users created successfully.";
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

// Insert admin user
$admin = new User();
$admin->id = 1;
$admin->first_name = "admin";
$admin->last_name = "user";
$admin->email = "admin@concordia.ca";
$admin->privilege = 0;
$admin->password_digest = password_hash('root', PASSWORD_DEFAULT);
$admin->save();
