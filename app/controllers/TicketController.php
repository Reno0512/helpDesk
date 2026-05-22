<?php
require './config/database.php';

$sql = "";

switch ($_SESSION["rol"]) {
    case 'admin':
        $sql = "SELECT * FROM tickets";
        break;
    case 'usuario':
        $sql = "SELECT * FROM tickets where area = '{$_SESSION['usuario']}'";
        break;
    default:
        //code block
}

if ($sql != null || $sql != '') {
    $tickets = $conn->query($sql);
}

require './app/views/tickets/index.php';

function verTicket()
{
    require './app/views/tickets/ver.php';
}
