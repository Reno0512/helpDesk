<?php

// require 'core/auth.php';
require './config/database.php';

if ($_POST) {

    $user = $_POST['usuario'];
    $pass = md5($_POST['password']);

    $sql = "SELECT * FROM usuarios
    WHERE usuario='$user'
    AND password='$pass'
    AND activo= '1'
    LIMIT 1";

    $r = $conn->query($sql);

    // var_dump($r);

    if ($r->num_rows > 0) {

        $u = $r->fetch_assoc();

        $_SESSION["id"] = $u["id"];
        $_SESSION["nombre"] = $u["nombre"];
        $_SESSION["usuario"] = $u["usuario"];
        $_SESSION["rol"] = $u["rol"];

        $rol = $u["rol"];
        
        if ($rol === "admin"){  
            header("location:/dashboard");
        }else {
            header("location:/tickets");
        }

        
        exit;
    } else {

        $error = "Usuario o contraseña incorrectas";
    }
}

require './app/views/auth/login.php';
