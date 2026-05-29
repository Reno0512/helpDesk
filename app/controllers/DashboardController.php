<?php

// require 'core/auth.php';
require './config/database.php';

$sql="SELECT count(*) total
FROM tickets
WHERE estatus='Nuevo'";

$res=$conn->query($sql);
$row=$res->fetch_assoc();

$abiertos=$row['total'];

$sql="SELECT count(*) total
FROM tickets
WHERE estatus='En Proceso'";

$res=$conn->query($sql);
$row=$res->fetch_assoc();

$enProceso=$row['total'];

$sql="SELECT count(*) total
FROM tickets
WHERE estatus='Urgente'";

$res=$conn->query($sql);
$row=$res->fetch_assoc();

$urgentes=$row['total'];




require './app/views/dashboard/index.php';