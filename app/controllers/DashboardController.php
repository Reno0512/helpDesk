<?php
require './config/database.php';

$sql="SELECT count(*) total
FROM tickets
WHERE estatus='Nuevo'";

$res=$conn->query($sql);
$row=$res->fetch_assoc();

$abiertos=$row['total'];

require './app/views/dashboard/index.php';