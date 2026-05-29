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


$reportante = $_POST["reportante"];
$titulo = $_POST["titulo"];
$area = $_SESSION["usuario"];
// $area = $_POST["area"];
$descripcion = $_POST["descripcion"];


$sql = "
INSERT INTO tickets(
folio,
reportante,
titulo,
area,
descripcion,
estatus
)

VALUES(
'$folio',
'$titulo',
'$area',
'$descripcion',
'Nuevo'
)
";


if ($conn->query($sql)) {
    echo $folio;
} else {
    echo "error";
}
