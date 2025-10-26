<?php

$host = 'localhost';
$user = 'root';
$password = 'Teb0h0@se';
$dbname = 'users_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>