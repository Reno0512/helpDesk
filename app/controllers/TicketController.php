<?php

require './config/database.php';

function indexTickets()
{
    global $conn;

    $sql = "";

    switch ($_SESSION["rol"]) {
        case 'admin':
            $sql = "SELECT t.*, e.nombre as nombre_estatus, c.nombre AS nombre_area FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus;";
            break;
        case 'tecnico':
            $sql = "SELECT t.*, e.nombre as nombre_estatus, c.nombre AS nombre_area FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus where tecnico_id = '{$_SESSION['id']}'";
            break;
        case 'usuario':
            $sql = "SELECT t.*,  e.nombre as nombre_estatus, c.nombre AS nombre_area FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus where area = '{$_SESSION['usuario']}'";
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

    $comentario = trim($_POST["comentario"]);

    /*
    Actualiza estatus actual del ticket
    */

    $actualizar = $conn->query("
        UPDATE tickets
        SET estatus_id='$estatus'
        WHERE id=$ticket_id
    ");

    /*
    Guarda movimiento en historial
    */

    $historial = $conn->query("
        INSERT INTO ticket_historial(
            ticket_id,
            estatus_id,
            comentario,
            usuario
        )
        VALUES(
            $ticket_id,
            '$estatus',
            '$comentario',
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

function cerrarTicket()
{
    global $conn;

    $ticket_id = $_POST["ticket_id"];

    $comentario = $_POST["comentario"];

    $solucion = $_POST["solucion"];

    $observaciones = $_POST["observaciones"];


    $conn->query("
    UPDATE tickets
    SET
        estatus='Cerrado',
        fecha_cierre=NOW()
    WHERE id=$ticket_id
    ");


    $conn->query("
    INSERT INTO ticket_historial(
        ticket_id,
        comentario,
        estatus,
        usuario
    )
    VALUES(
        $ticket_id,
        '$comentario',
        'Cerrado',
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

    // echo "
    // <script>

    //     window.open(
    //         '/pdf_ticket/$ticket_id',
    //         '_blank'
    //     );

    //     window.location.href =
    //         '/ver_ticket/$ticket_id';

    // </script>
    // ";

    header(
        "Location:/pdf_ticket/$ticket_id"
    );
}


require_once './vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


function generarPDF()
{
    global $conn;

    $id = $_GET["id"];

    $ticket = $conn->query("
        SELECT folio,areas.nombre AS area, titulo,descripcion,estatus,fecha_cierre,solucion FROM tickets inner join ticket_cierre on tickets.id=ticket_cierre.ticket_id inner join cat_areas areas on tickets.area_id=areas.id_area WHERE tickets.id=$id
    ")->fetch_assoc();

    ob_start();

    $rutaRaizProyecto = dirname(__DIR__, 2);

    // Tu ruta exacta hacia la imagen
    $rutaImagen = $rutaRaizProyecto . '/assets/img/membrete.png';

    $rutaFirma = $rutaRaizProyecto . '/assets/img/firmaRodo.png';

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

    $dompdf->stream(
        "Ticket_" . $ticket["folio"] . ".pdf",
        ["Attachment" => false]
    );
}


function pdfTest()
{

    // 2. Configurar opciones esenciales para imágenes
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Requerido para URLs y rutas con http://

    $dompdf = new Dompdf($options);

    /// Sube 2 niveles para obtener la raíz de tu proyecto
    $rutaRaizProyecto = dirname(__DIR__, 2);

    // AUTORIZACIÓN: Permite a Dompdf leer archivos dentro de la raíz de tu proyecto
    $options->setChroot($rutaRaizProyecto);

    $dompdf = new Dompdf($options);

    // Tu ruta exacta hacia la imagen
    $rutaImagen = $rutaRaizProyecto . '/assets/img/JumilOficial.png';

    // 5. Crear el HTML
    $html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; }
        .logo { width: 150px; height: auto; }
    </style>
</head>
<body>
    <h1>Reporte con Imagen</h1>
    <!-- Usamos la variable con la ruta absoluta -->
    <img class="logo" src="' . $rutaImagen . '" alt="Logo">
</body>
</html>
';

    // 5. Renderizar y mostrar el PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    while (ob_get_level()) {
        ob_end_clean();
    }

    // Forzar la descarga del PDF en el navegador
    $dompdf->stream("documento.pdf", ["Attachment" => false]);
}
