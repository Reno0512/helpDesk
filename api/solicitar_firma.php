<?php

require '../config/database.php';

header(
    'Content-Type: text/plain; charset=utf-8'
);


/*
|--------------------------------------------------------------------------
| DATOS
|--------------------------------------------------------------------------
*/

$ticket_id =
    (int) ($_POST["ticket_id"] ?? 0);

$comentario =
    trim($_POST["comentario"] ?? '');

$solucion =
    trim($_POST["solucion"] ?? '');

$observaciones =
    trim($_POST["observaciones"] ?? '');


if ($ticket_id <= 0) {

    http_response_code(400);

    exit("Ticket inválido");
}


/*
|--------------------------------------------------------------------------
| GUARDAR EVIDENCIA
|--------------------------------------------------------------------------
*/

$imagen = null;


if (
    isset($_FILES["imagen"]) &&
    $_FILES["imagen"]["error"] === UPLOAD_ERR_OK
) {

    $directorio =
        '../uploads/seguimientos/';


    if (!is_dir($directorio)) {

        mkdir(
            $directorio,
            0755,
            true
        );
    }


    $finfo =
        new finfo(
            FILEINFO_MIME_TYPE
        );


    $mime =
        $finfo->file(
            $_FILES["imagen"]["tmp_name"]
        );


    $tiposPermitidos = [

        'image/jpeg' => 'jpg',

        'image/png' => 'png',

        'image/webp' => 'webp'

    ];


    if (!isset($tiposPermitidos[$mime])) {

        http_response_code(400);

        exit("El archivo debe ser JPG, PNG o WEBP.");
    }


    $extension =
        $tiposPermitidos[$mime];


    $nombreImagen =

        'ticket_' .

        $ticket_id .

        '_cierre_' .

        date('YmdHis') .

        '_' .

        bin2hex(
            random_bytes(4)
        ) .

        '.' .

        $extension;


    $rutaFisica =

        $directorio .

        $nombreImagen;


    if (

        !move_uploaded_file(

            $_FILES["imagen"]["tmp_name"],

            $rutaFisica

        )

    ) {

        http_response_code(500);

        exit("No fue posible guardar la evidencia.");
    }


    /*
    Ruta guardada en BD
    */

    $imagen =

        '/uploads/seguimientos/' .

        $nombreImagen;
}


/*
|--------------------------------------------------------------------------
| GENERAR TOKEN
|--------------------------------------------------------------------------
*/

$token =

    bin2hex(

        random_bytes(32)

    );


/*
|--------------------------------------------------------------------------
| GUARDAR HISTORIAL
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("

    INSERT INTO ticket_historial(

        ticket_id,

        comentario,

        estatus_id,

        usuario,

        imagen

    )

    VALUES(

        ?,

        ?,

        3,

        '',

        ?

    )

");


$stmt->bind_param(

    "iss",

    $ticket_id,

    $comentario,

    $imagen

);


$stmt->execute();


/*
|--------------------------------------------------------------------------
| ACTUALIZAR TICKET
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("

    UPDATE tickets

    SET

        estatus_id = 3,

        token_firma = ?

    WHERE id = ?

");


$stmt->bind_param(

    "si",

    $token,

    $ticket_id

);


$stmt->execute();


/*
|--------------------------------------------------------------------------
| GUARDAR CIERRE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("

    INSERT INTO ticket_cierre(

        ticket_id,

        solucion,

        observaciones

    )

    VALUES(

        ?,

        ?,

        ?

    )

");


$stmt->bind_param(

    "iss",

    $ticket_id,

    $solucion,

    $observaciones

);


$stmt->execute();


/*
|--------------------------------------------------------------------------
| RESPUESTA
|--------------------------------------------------------------------------
*/

echo $token;

exit;
