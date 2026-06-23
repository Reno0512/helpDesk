<?php

require '../config/database.php';

$ticket_id = $_POST["ticket_id"];

$comentario = $_POST["comentario"];

$solucion = $_POST["solucion"];

$observaciones = $_POST["observaciones"];


$conn->query("
    INSERT INTO ticket_historial(
        ticket_id,
        comentario,
        estatus_id,
        usuario
    )
    VALUES(
        $ticket_id,
        '$comentario',
        '3',
        ''
    )
    ");

$token =
    bin2hex(
        random_bytes(32)
    );

$conn->query("
    UPDATE tickets
    SET
        estatus_id=3,
        token_firma='$token'
    WHERE id=$ticket_id
");


$conn->query("
    INSERT INTO ticket_cierre(
        ticket_id,
        solucion,
        observaciones
    )
    VALUES(
        $ticket_id,
        '$solucion',
        '$observaciones'
    )
    ");

// echo json_encode([
//     "ok" => true,
//     "token" => $token
// ]);

echo $token;
