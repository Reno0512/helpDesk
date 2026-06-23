<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// require __DIR__ . '/../config/database.php';
require '../config/database.php';

$ultimo =
    $conn->query("
SELECT id
FROM tickets
ORDER BY id DESC
LIMIT 1
");


if ($ultimo && $ultimo->num_rows > 0) {
    $row = $ultimo->fetch_assoc();
    $next = $row["id"] + 1;
} else {
    // Si la tabla está vacía o la consulta falla, el siguiente id será 1
    $next = 1;
}

// $row = $ultimo->fetch_assoc();

// $next = ($row["id"] ?? 0) + 1;


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
// $area = $_SESSION["usuario"];
$area = $_POST["area"];
$descripcion = $_POST["descripcion"];
$tecnico_id = $_POST["usuario_id"];
$rol = $_POST["rol"];

$nombreImagen = null;

if (
    isset($_FILES["evidencia"]) &&
    $_FILES["evidencia"]["error"] == 0
) {

    $extension = pathinfo(
        $_FILES["evidencia"]["name"],
        PATHINFO_EXTENSION
    );

    $nombreImagen =
        $folio
        . "."
        . $extension;

    move_uploaded_file(
        $_FILES["evidencia"]["tmp_name"],
        "../uploads/tickets/" . $nombreImagen
    );
}

switch ($rol) {
    case 'tecnico':
        $sql = "INSERT INTO tickets(folio,reportante,titulo,area_id,descripcion,estatus_id,tecnico_id, evidencia) VALUES ('$folio','$reportante','$titulo','$area','$descripcion',1,'$tecnico_id','$nombreImagen')";
        break;
    default:
        $sql = "INSERT INTO tickets(folio,reportante,titulo,area_id,descripcion,estatus_id,evidencia) VALUES ('$folio','$reportante','$titulo','$area','$descripcion',1,'$nombreImagen')";
}


if ($conn->query($sql)) {
    echo $folio;
} else {
    echo "error";
}
