<?php

$host = "m4t.447.mytemp.website";
$user = "admin_apoyos";
$pass = "T4xc02o25*";
$db = "help_desk";

// $host = "localhost";
// $user = "root";
// $pass = "";
// $db = "help_desk";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error conexion");
}
