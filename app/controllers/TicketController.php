<?php

require './config/database.php';

function indexTickets()
{
    global $conn;

    $sql = "";

    switch ($_SESSION["rol"]) {
        case 'admin':
            $sql = "SELECT t.id, t.folio, t.titulo, c.nombre AS nombre_area, prioridad, e.nombre as nombre_estatus, u.nombre AS tecnico
                    FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area 
                    INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus 
                    LEFT JOIN usuarios u ON t.tecnico_id = u.id
                    order BY t.id desc;";
            break;
        case 'tecnico':
            $sql = "SELECT t.*, e.nombre as nombre_estatus, c.nombre AS nombre_area, u.nombre AS tecnico 
            FROM tickets t 
            INNER JOIN cat_areas c ON t.area_id = c.id_area 
            INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus
            LEFT JOIN usuarios u ON t.tecnico_id = u.id
            where (tecnico_id = '{$_SESSION['id']}' OR tecnico_id = '' OR tecnico_id IS NULL) order by id desc;";
            break;
        case 'usuario':
            $sql = "SELECT t.*,  e.nombre as nombre_estatus, c.nombre AS nombre_area FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus where area = '{$_SESSION['usuario']}' order by id desc;";
            break;
    }



    if ($sql != null || $sql != '') {
        $tickets = $conn->query($sql);
    }

    $sql = "SELECT * FROM cat_areas";

    $areas_principales = $conn->query($sql);


    require './app/views/tickets/index.php';
}

function verTicket()
{
    global $conn;

    $id = $_GET["id"];

    // $sql = "
    // SELECT *    
    // FROM tickets
    // WHERE id = $id
    // ";

    $sql = "SELECT t.*, e.nombre as nombre_estatus, c.nombre AS nombre_area FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus WHERE id = $id;";

    $ticket = $conn
        ->query($sql)
        ->fetch_assoc();

    $sql = "SELECT * from cat_estatus";

    $estatus_cat = $conn
        ->query($sql);

    $historial = $conn->query("
        SELECT t.*, e.nombre as nombre_estatus
        FROM ticket_historial t
        INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus
        WHERE ticket_id=$id
        ORDER BY fecha DESC
    ");


    require './app/views/tickets/ver.php';
}

function guardarSeguimiento()
{

    global $conn;

    $ticket_id = $_POST["ticket_id"];
    $estatus = $_POST["estatus"];
    $prioridad = $_POST["prioridad"];

    $comentario = trim($_POST["comentario"]);

    /*
    Actualiza estatus actual del ticket
    */

    $actualizar = $conn->query("
        UPDATE tickets
        SET estatus_id='$estatus', prioridad='$prioridad'
        WHERE id=$ticket_id
    ");

    /*
    Guarda movimiento en historial
    */

    $rutaImagen = null;

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $extension = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));

        $nombreArchivo = uniqid() . "." . $extension;

        $rutaDestino = "./uploads/seguimientos/" . $nombreArchivo;

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);

        $rutaImagen = "/uploads/seguimientos/" . $nombreArchivo;
    }

    $historial = $conn->query("
        INSERT INTO ticket_historial(
            ticket_id,
            estatus_id,
            comentario,
            imagen,
            usuario
        )
        VALUES(
            $ticket_id,
            '$estatus',
            '$comentario',
            " . ($rutaImagen ? "'$rutaImagen'" : "NULL") . ",
            '{$_SESSION['usuario']}'
        )
    ");

    if ($actualizar && $historial) {

        $_SESSION['mensaje'] = "Seguimiento guardado correctamente.";
        $_SESSION['tipo_mensaje'] = "success";
    } else {

        $_SESSION['mensaje'] = "Error al guardar seguimiento: " . $conn->error;
        $_SESSION['tipo_mensaje'] = "danger";
    }

    header(
        "Location:/ver_ticket/$ticket_id"
    );

    exit;
}

// function cerrarTicket()
// {
//     global $conn;

//     $ticket_id = $_POST["ticket_id"];

//     $comentario = $_POST["comentario"];

//     $solucion = $_POST["solucion"];

//     $observaciones = $_POST["observaciones"];


//     $conn->query("
//     UPDATE tickets
//     SET
//         estatus='4',
//         fecha_cierre=NOW()
//     WHERE id=$ticket_id
//     ");


//     $conn->query("
//     INSERT INTO ticket_historial(
//         ticket_id,
//         comentario,
//         estatus,
//         usuario
//     )
//     VALUES(
//         $ticket_id,
//         '$comentario',
//         '4',
//         '{$_SESSION['usuario']}'
//     )
//     ");


//     $conn->query("
//     INSERT INTO ticket_cierre(
//         ticket_id,
//         solucion,
//         observaciones
//     )
//     VALUES(
//         $ticket_id,
//         '$solucion',
//         '$observaciones'
//     )
//     ");

//     // echo "
//     // <script>

//     //     window.open(
//     //         '/pdf_ticket/$ticket_id',
//     //         '_blank'
//     //     );

//     //     window.location.href =
//     //         '/ver_ticket/$ticket_id';

//     // </script>
//     // ";

//     header(
//         "Location:/pdf_ticket/$ticket_id"
//     );
// }

function guardarImagenFirma($firmaBase64, $ticket_id)
{

    // Quitar encabezado del Base64
    $firmaBase64 = str_replace(
        'data:image/png;base64,',
        '',
        $firmaBase64
    );

    $firmaBase64 = str_replace(
        ' ',
        '+',
        $firmaBase64
    );

    // Decodificar imagen
    $imagen = base64_decode(
        $firmaBase64
    );

    // Crear carpeta si no existe
    $directorio =
        './uploads/firmas/';

    if (!is_dir($directorio)) {

        mkdir(
            $directorio,
            0777,
            true
        );
    }

    // Nombre único
    $nombreArchivo =
        'solicitante_' .
        $ticket_id .
        '_' .
        date('YmdHis') .
        '.png';

    $rutaCompleta =
        $directorio .
        $nombreArchivo;

    // Guardar archivo
    file_put_contents(
        $rutaCompleta,
        $imagen
    );

    // Ruta que se guardará en BD
    return
        'uploads/firmas/' .
        $nombreArchivo;
}

function guardarFirmaSolicitante()
{
    global $conn;

    $ticket_id = $_POST["ticket_id"];

    $comentario = $_POST["comentario"];

    $solucion = $_POST["solucion"];

    $observaciones = $_POST["observaciones"];

    $ticket_id =
        $_POST["ticket_id"];

    $firma =
        $_POST["firma"];

    $firma =
        str_replace(
            'data:image/png;base64,',
            '',
            $firma
        );

    $firma =
        str_replace(
            ' ',
            '+',
            $firma
        );

    $data =
        base64_decode($firma);

    $archivo =
        './uploads/firmas/' .
        'solicitante_' .
        $ticket_id .
        '.png';

    file_put_contents(
        $archivo,
        $data
    );

    $conn->query("
        INSERT INTO ticket_firmas(
            ticket_id,
            firma_solicitante
        )
        VALUES(
            $ticket_id,
            '$archivo'
        )
    ");

    $conn->query("
        UPDATE tickets
        SET
            estatus_id='4',
            fecha_cierre=NOW()
        WHERE id=$ticket_id
    ");



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
        '4',
        '{$_SESSION['usuario']}'
    )
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

    echo json_encode([
        'ok' => true
    ]);

    exit;
}

// function solicitarFirma()
// {
//     global $conn;

//     $ticket_id = $_POST["ticket_id"];

//     $token =
//         bin2hex(
//             random_bytes(32)
//         );

//     // $conn->query("
//     //     UPDATE tickets
//     //     SET
//     //         estatus_id=3,
//     //         token_firma='$token'
//     //     WHERE id=$ticket_id
//     // ");

//     // echo json_encode([
//     //     "ok" => true,
//     //     "token" => $token
//     // ]);

//     echo $token;
// }

function firmaTicket()
{
    global $conn;

    $token =
        $_GET["token"];

    $ticket =
        $conn->query("
        SELECT t.id, t.folio, t.titulo, t.descripcion, tc.solucion, tc.observaciones
        FROM tickets t
        INNER JOIN ticket_cierre tc ON t.id=tc.ticket_id
        WHERE token_firma='$token'
    ")->fetch_assoc();

    if (!$ticket) {

        die("Enlace inválido");
    }

    require
        './app/views/tickets/firma.php';
}


function guardarFirmaRemoto()
{
    global $conn;

    $ticket_id =
        $_POST["ticket_id"];

    $firma =
        $_POST["firma"];

    $archivo =
        guardarImagenFirma(
            $firma,
            $ticket_id
        );

    $conn->query("
        INSERT INTO ticket_firmas(
            ticket_id,
            firma_solicitante
        )
        VALUES(
            $ticket_id,
            '$archivo'
        )
    ");

    $conn->query("
        UPDATE tickets
        SET
            estatus_id=4,
            fecha_cierre=NOW()
        WHERE id=$ticket_id
    ");

    echo json_encode([
        "ok" => true
    ]);
}


require_once './vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


function generarPDF()
{

    global $conn;

    $id = $_GET["id"];

    $ticket = $conn->query("SELECT folio, a.nombre AS area, t.reportante, titulo, descripcion, e.nombre AS estatus, fecha_cierre, solucion, evidencia  
                            FROM tickets t
                            INNER JOIN ticket_cierre tc on t.id=tc.ticket_id 
                            INNER JOIN cat_areas a on t.area_id=a.id_area 
                            INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus 
                            WHERE t.id=$id
    ")->fetch_assoc();

    // var_dump($ticket);
    // exit;

    $ultimoSeguimiento = $conn->query("
    SELECT *
    FROM ticket_historial
    WHERE ticket_id = $id
    AND imagen IS NOT NULL
    AND imagen <> ''
    ORDER BY id DESC
    LIMIT 1
    ")->fetch_assoc();

    $rutaImagenCierre = '';

    ob_start();

    $rutaRaizProyecto = dirname(__DIR__, 2);

    // Tu ruta exacta hacia la imagen
    $rutaImagen = $rutaRaizProyecto . '/assets/img/membrete.png';

    $rutaFirma = $rutaRaizProyecto . $conn->query("SELECT u.firma FROM tickets t 
                            INNER JOIN usuarios u ON t.tecnico_id=u.id
                                WHERE t.id=$id")->fetch_object()->firma;

    // $rutaFirma = $rutaRaizProyecto . '/assets/img/firmaRodo.png';

    $firma = $conn->query("
    SELECT *
    FROM ticket_firmas
    WHERE ticket_id=$id
    ORDER BY id DESC
    LIMIT 1
    ")->fetch_assoc();

    include './app/views/pdf/cierre_ticket.php';

    $html = ob_get_clean();

    $options = new Options();

    $options->set('isRemoteEnabled', true);

    // AUTORIZACIÓN: Permite a Dompdf leer archivos dentro de la raíz de tu proyecto
    $options->setChroot($rutaRaizProyecto);

    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html);

    $dompdf->setPaper('letter', 'portrait');

    $dompdf->render();

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: application/pdf');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    // $pdf = "uploads/pdf/Ticket_" . $ticket["folio"] . ".pdf";

    // $conn->query("
    // UPDATE tickets
    // SET pdf_cierre='$pdf'
    // WHERE id=$id
    // ");

    $dompdf->stream(
        "Ticket_" . $ticket["folio"] . ".pdf",
        ["Attachment" => false]
    );

    exit;
}


// function pdfTest()
// {

//     // 2. Configurar opciones esenciales para imágenes
//     $options = new Options();
//     $options->set('isHtml5ParserEnabled', true);
//     $options->set('isRemoteEnabled', true); // Requerido para URLs y rutas con http://

//     $dompdf = new Dompdf($options);

//     /// Sube 2 niveles para obtener la raíz de tu proyecto
//     $rutaRaizProyecto = dirname(__DIR__, 2);

//     // AUTORIZACIÓN: Permite a Dompdf leer archivos dentro de la raíz de tu proyecto
//     $options->setChroot($rutaRaizProyecto);

//     $dompdf = new Dompdf($options);

//     // Tu ruta exacta hacia la imagen
//     $rutaImagen = $rutaRaizProyecto . '/assets/img/JumilOficial.png';

//     // 5. Crear el HTML
//     $html = '
// <!DOCTYPE html>
// <html lang="es">
// <head>
//     <meta charset="UTF-8">
//     <style>
//         body { font-family: sans-serif; }
//         .logo { width: 150px; height: auto; }
//     </style>
// </head>
// <body>
//     <h1>Reporte con Imagen</h1>
//     <!-- Usamos la variable con la ruta absoluta -->
//     <img class="logo" src="' . $rutaImagen . '" alt="Logo">
// </body>
// </html>
// ';

//     // 5. Renderizar y mostrar el PDF
//     $dompdf->loadHtml($html);
//     $dompdf->setPaper('A4', 'portrait');
//     $dompdf->render();

//     while (ob_get_level()) {
//         ob_end_clean();
//     }

//     // Forzar la descarga del PDF en el navegador
//     $dompdf->stream("documento.pdf", ["Attachment" => false]);
// }
