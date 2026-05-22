<?php

require '../config/database.php';

$ultimo =
    $conn->query("
SELECT id
FROM tickets
ORDER BY id DESC
LIMIT 1
");

$row = $ultimo->fetch_assoc();

$next = ($row["id"] ?? 0) + 1;


$folio =
    'TIC-' . date('Y') . '-' .
    str_pad(
        $next,
        5,
        '0',
        STR_PAD_LEFT
    );


$titulo = $_POST["titulo"];
$area = $_POST["area"];
$descripcion = $_POST["descripcion"];
$prioridad = $_POST["prioridad"];


$sql = "
INSERT INTO tickets(
folio,
titulo,
area,
descripcion,
prioridad,
estatus
)

VALUES(
'$folio',
'$titulo',
'$area',
'$descripcion',
'$prioridad',
'Nuevo'
)
";


if ($conn->query($sql)) {
    echo $folio;
} else {
    echo "error";
}
