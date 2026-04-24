<?php
require './config/database.php';

$sql="SELECT * FROM tickets";
$tickets=$conn->query($sql);

require './app/views/tickets/index.php';