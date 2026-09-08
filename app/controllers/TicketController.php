<?php

require './config/database.php';

function indexTickets()
{
	require_once './core/Auth.php';

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
			-- where (tecnico_id = '{$_SESSION['id']}' OR tecnico_id = '' OR tecnico_id IS NULL) 
			order by id desc;";
			break;
		case 'usuario':
			$sql = "SELECT t.*,  e.nombre as nombre_estatus, c.nombre AS nombre_area FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus where area = '{$_SESSION['usuario']}' order by id desc;";
			break;
		case 'asistente':
			$sql = "SELECT t.id, t.folio, t.titulo, c.nombre AS nombre_area, prioridad, e.nombre as nombre_estatus, u.nombre AS tecnico
					FROM tickets t INNER JOIN cat_areas c ON t.area_id = c.id_area
					INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus
					LEFT JOIN usuarios u ON t.tecnico_id = u.id
					order BY t.id desc;";
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
	require_once './core/Auth.php';

	global $conn;

	$id = $_GET["id"];

	// $sql = "
	// SELECT *
	// FROM tickets
	// WHERE id = $id
	// ";

	$sql = "SELECT t.*, e.nombre as nombre_estatus, c.nombre AS nombre_area 
			FROM tickets t 
			INNER JOIN cat_areas c ON t.area_id = c.id_area 
			INNER JOIN cat_estatus e ON t.estatus_id = e.id_estatus WHERE id = $id;";

	$ticket = $conn
		->query($sql)
		->fetch_assoc();

	$sql = "SELECT * from cat_estatus";

	$estatus_cat = $conn
		->query($sql);

	$sql = "SELECT 
                id,
                nombre
            FROM usuarios
            WHERE rol = 'tecnico'
            ORDER BY nombre ASC";

	$tecnicos = $conn
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

	require_once './core/Auth.php';

	global $conn;

	$ticket_id = $_POST["ticket_id"];
	$estatus = $_POST["estatus"];
	$prioridad = $_POST["prioridad"];
	$tecnico_id = $_POST['tecnico_id'];

	$comentario = trim($_POST["comentario"]);

	/*
	Actualiza estatus actual del ticket
	*/

	$actualizar = $conn->query("
		UPDATE tickets
		SET 
		estatus_id='$estatus', 
		prioridad='$prioridad',
		tecnico_id='$tecnico_id'
		WHERE id=$ticket_id
	");

	/*
		Agregar funcionalidad de guardar imagen en los botones de firma
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
	require_once './core/Auth.php';

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

	$rutaImagen = null;

	if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
		$extension = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));

		$nombreArchivo = uniqid() . "." . $extension;

		$rutaDestino = "./uploads/seguimientos/" . $nombreArchivo;

		move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);

		$rutaImagen = "/uploads/seguimientos/" . $nombreArchivo;
	}


	$conn->query("
	INSERT INTO ticket_historial(
		ticket_id,
		comentario,
		estatus_id,
		usuario,
		imagen
	)
	VALUES(
		$ticket_id,
		'$comentario',
		'4',
		'{$_SESSION['usuario']}',
		" . ($rutaImagen ? "'$rutaImagen'" : "NULL") . "
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

	require_once './core/Auth.php';

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

function nuevoTicketPublico()
{
	global $conn;

	$sql = "SELECT *
            FROM cat_areas
            ORDER BY nombre ASC";

	$areas = $conn->query($sql);

	require './app/views/tickets/nuevo.php';
}

function guardarTicketPublico()
{
	global $conn;

	$reportante  = trim($_POST['reportante'] ?? '');
	$telefono    = trim($_POST['telefono'] ?? '');
	$area_id     = (int)($_POST['area_id'] ?? 0);
	$titulo      = trim($_POST['titulo'] ?? '');
	$descripcion = trim($_POST['descripcion'] ?? '');


	// ==========================================
	// VALIDAR DATOS
	// ==========================================

	if (
		$reportante === '' ||
		$telefono === '' ||
		$area_id <= 0 ||
		$titulo === '' ||
		$descripcion === ''
	) {
		die('Todos los campos obligatorios deben ser completados.');
	}


	// ==========================================
	// OBTENER ESTATUS "Nuevo"
	// ==========================================

	$sqlEstatus = "
        SELECT id_estatus
        FROM cat_estatus
        WHERE nombre = 'Nuevo'
        LIMIT 1
    ";

	$resultadoEstatus = $conn->query($sqlEstatus);

	if (
		!$resultadoEstatus ||
		$resultadoEstatus->num_rows === 0
	) {
		die('No se encontró el estatus Nuevo.');
	}

	$estatus = $resultadoEstatus->fetch_assoc();

	$estatus_id = (int)$estatus['id_estatus'];


	// ==========================================
	// INSERTAR TICKET
	// ==========================================

	$stmt = $conn->prepare("
        INSERT INTO tickets (
            titulo,
            reportante,
            telefono,
            area_id,
            descripcion,
            prioridad,
            estatus_id,
            usuario_id,
            tecnico_id
        )
        VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            'Media',
            ?,
            NULL,
            NULL
        )
    ");

	$stmt->bind_param(
		"sssisi",
		$titulo,
		$reportante,
		$telefono,
		$area_id,
		$descripcion,
		$estatus_id
	);


	if (!$stmt->execute()) {

		die('No fue posible registrar el ticket: ' .
			$stmt->error);
	}


	// ==========================================
	// ID GENERADO
	// ==========================================

	$ticket_id = $stmt->insert_id;


	// ==========================================
	// GENERAR FOLIO
	// ==========================================

	$next = $ticket_id;

	$folio =
		'TCK-' . date('Y') . '-' .
		str_pad(
			$next,
			5,
			'0',
			STR_PAD_LEFT
		);


	// ==========================================
	// EVIDENCIA
	// ==========================================

	$nombreEvidencia = null;

	if (
		isset($_FILES['evidencia']) &&
		$_FILES['evidencia']['error'] === UPLOAD_ERR_OK
	) {

		$archivo = $_FILES['evidencia'];


		// --------------------------------------
		// VALIDAR MIME
		// --------------------------------------

		$permitidos = [
			'image/jpeg' => 'jpg',
			'image/png'  => 'png',
			'image/webp' => 'webp'
		];


		$finfo = finfo_open(FILEINFO_MIME_TYPE);

		$mime = finfo_file(
			$finfo,
			$archivo['tmp_name']
		);

		finfo_close($finfo);


		if (!isset($permitidos[$mime])) {

			die('El archivo seleccionado no es una imagen válida.');
		}


		// --------------------------------------
		// NOMBRE DE LA IMAGEN
		// --------------------------------------

		$extension = $permitidos[$mime];

		$nombreEvidencia =
			$folio . '.' . $extension;


		// --------------------------------------
		// DIRECTORIO
		// --------------------------------------

		$directorio =
			__DIR__ .
			'/../../uploads/tickets/';


		if (!is_dir($directorio)) {

			if (!mkdir($directorio, 0755, true)) {

				die('No fue posible crear el directorio de evidencias.');
			}
		}


		// --------------------------------------
		// GUARDAR IMAGEN
		// --------------------------------------

		if (!move_uploaded_file(
			$archivo['tmp_name'],
			$directorio . $nombreEvidencia
		)) {

			die('No fue posible guardar la evidencia.');
		}
	}


	// ==========================================
	// ACTUALIZAR FOLIO Y EVIDENCIA
	// ==========================================

	$stmtFolio = $conn->prepare("
        UPDATE tickets
        SET
            folio = ?,
            evidencia = ?
        WHERE id = ?
    ");


	$stmtFolio->bind_param(
		"ssi",
		$folio,
		$nombreEvidencia,
		$ticket_id
	);


	if (!$stmtFolio->execute()) {

		die('El ticket fue creado pero no se pudo actualizar el folio.');
	}


	// ==========================================
	// OBTENER NOMBRE DEL ÁREA
	// ==========================================

	$stmtArea = $conn->prepare("
        SELECT nombre
        FROM cat_areas
        WHERE id_area = ?
        LIMIT 1
    ");

	$stmtArea->bind_param(
		"i",
		$area_id
	);

	$stmtArea->execute();

	$resultadoArea = $stmtArea->get_result();

	$area = $resultadoArea->fetch_assoc();

	$nombreArea = $area
		? $area['nombre']
		: 'Área no especificada';


	// ==========================================
	// DATOS PARA LA VISTA
	// ==========================================

	$ticket = [
		'id' => $ticket_id,
		'folio' => $folio,
		'reportante' => $reportante,
		'telefono' => $telefono,
		'titulo' => $titulo,
		'descripcion' => $descripcion,
		'prioridad' => 'Media',
		'nombre_area' => $nombreArea,
		'fecha' => date('Y-m-d H:i:s'),
		'evidencia' => $nombreEvidencia
	];


	// ==========================================
	// MOSTRAR REGISTRADO
	// ==========================================

	require './app/views/tickets/registrado.php';
}

function consultarTicket()
{
	global $conn;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$folio = trim($_POST['folio'] ?? '');

		if ($folio === '') {

			$error = "Debes ingresar un folio.";

			require './app/views/tickets/consultar.php';

			return;
		}

		// ==========================================
		// OBTENER TICKET
		// ==========================================

		$stmt = $conn->prepare("
            SELECT
                t.id,
                t.folio,
                t.titulo,
                t.reportante,
                t.descripcion,
                t.prioridad,
                t.fecha,
                t.fecha_cierre,
                e.nombre AS nombre_estatus,
                a.nombre AS nombre_area
            FROM tickets t

            INNER JOIN cat_estatus e
                ON t.estatus_id = e.id_estatus

            INNER JOIN cat_areas a
                ON t.area_id = a.id_area

            WHERE t.folio = ?

            LIMIT 1
        ");

		$stmt->bind_param("s", $folio);

		$stmt->execute();

		$resultado = $stmt->get_result();

		$ticket = $resultado->fetch_assoc();


		// ==========================================
		// TICKET NO ENCONTRADO
		// ==========================================

		if (!$ticket) {

			$error = "No se encontró ningún ticket con el folio proporcionado.";

			require './app/views/tickets/consultar.php';

			return;
		}


		// ==========================================
		// OBTENER HISTORIAL
		// ==========================================

		$historial = $conn->query("
            SELECT
                h.*,
                e.nombre AS nombre_estatus
            FROM ticket_historial h

            INNER JOIN cat_estatus e
                ON h.estatus_id = e.id_estatus

            WHERE h.ticket_id = {$ticket['id']}

            ORDER BY h.fecha ASC
        ");


		// ==========================================
		// MOSTRAR RESULTADO
		// ==========================================

		require './app/views/tickets/resultado.php';

		return;
	}


	// ==========================================
	// MOSTRAR FORMULARIO
	// ==========================================

	require './app/views/tickets/consultar.php';
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
