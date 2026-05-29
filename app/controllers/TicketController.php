<?php

require './config/database.php';

function indexTickets()
{
    global $conn;

    $sql = "";

    switch ($_SESSION["rol"]) {
        case 'admin':
            $sql = "SELECT * FROM tickets";
            break;
        case 'usuario':
            $sql = "SELECT * FROM tickets where area = '{$_SESSION['usuario']}'";
            break;
    }

    if ($sql != null || $sql != '') {
        $tickets = $conn->query($sql);
    }

    require './app/views/tickets/index.php';
}

function verTicket()
{
    global $conn;

    $id = $_GET["id"];

    $sql = "
    SELECT *
    FROM tickets
    WHERE id = $id
    ";

    $ticket = $conn
        ->query($sql)
        ->fetch_assoc();

    require './app/views/tickets/ver.php';
}
