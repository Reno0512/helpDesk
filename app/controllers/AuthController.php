<?php

require 'core/auth.php';
require 'config/database.php';

if ($_POST) {

    $user = $_POST['usuario'];
    $pass = md5($_POST['password']);

    $sql = "SELECT * FROM usuarios
WHERE usuario='$user'
AND password='$pass'
LIMIT 1";

    $r = $conn->query($sql);

    if ($r->num_rows > 0) {

        $u = $r->fetch_assoc();

        $_SESSION["id"] = $u["id"];
        $_SESSION["nombre"] = $u["nombre"];
        $_SESSION["rol"] = $u["rol"];

        header("location:/dashboard");
        exit;
    } else {

        $error = "Credenciales incorrectas";
    }
}

require './app/views/auth/login.php';
