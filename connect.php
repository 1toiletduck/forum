<?php

$host = "localhost";
$username = "username";
$password = "password";
$databaseName = "database_name";

// Create a connection using mysqli
$mysqli = new mysqli($host, $username, $password, $databaseName);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>
