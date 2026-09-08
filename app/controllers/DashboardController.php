<?php

// require 'core/auth.php';
require './config/database.php';
require './core/Auth.php';

if ($_SESSION["rol"] == 'admin') {
    $sql = "SELECT count(*) total 
    FROM tickets
    WHERE estatus_id=1";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $abiertos = $row['total'];

    $sql = "SELECT count(*) total
    FROM tickets
    WHERE estatus_id=2";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $enProceso = $row['total'];


    $sql = "SELECT count(*) total
    FROM tickets
    WHERE estatus_id=3";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $pendientes = $row['total'];

    $sql = "SELECT count(*) total
    FROM tickets
    WHERE estatus_id=4";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $cerrados = $row['total'];
} else {
    $sql = "SELECT count(*) total 
    FROM tickets
    WHERE estatus_id=1 AND tecnico_id = '{$_SESSION['id']}'";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $abiertos = $row['total'];

    $sql = "SELECT count(*) total
    FROM tickets
    WHERE estatus_id=2 AND tecnico_id = '{$_SESSION['id']}'";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $enProceso = $row['total'];


    $sql = "SELECT count(*) total
    FROM tickets
    WHERE estatus_id=3 AND tecnico_id = '{$_SESSION['id']}'";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $pendientes = $row['total'];

    $sql = "SELECT count(*) total
    FROM tickets
    WHERE estatus_id=4 AND tecnico_id = '{$_SESSION['id']}'";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $cerrados = $row['total'];
}

require './app/views/dashboard/index.php';
