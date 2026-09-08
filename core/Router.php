<?php

$uri = trim(
    parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    ),
    '/'
);

/*
Si el proyecto está en
localhost/helpDesk
descomentar esto:

$uri = str_replace(
'helpDesk/',
'',
$uri
);
*/
// echo $uri;

switch (true) {

    case $uri == '':

    case $uri == 'login':
        require __DIR__ . '/../app/controllers/AuthController.php';
        break;

    case $uri == 'dashboard':
        require __DIR__ . '/../app/controllers/DashboardController.php';
        break;

    case $uri == 'tickets':
        require __DIR__ . '/../app/controllers/TicketController.php';
        indexTickets();
        break;

    case $uri == 'actualizar_ticket':
        require __DIR__ . '/../app/controllers/TicketController.php';
        guardarSeguimiento();
        break;

    // case $uri == 'ver_ticket/':
    //     require './app/controllers/TicketController.php';
    //     verTicket();
    //     break;

    case preg_match('/^ver_ticket\/([0-9]+)$/', $uri, $matches):

        $_GET["id"] = $matches[1];

        require __DIR__ . '/../app/controllers/TicketController.php';

        verTicket();

        break;

    case $uri == 'guardar_firma':

        require './app/controllers/TicketController.php';

        guardarFirmaSolicitante();

        break;

    case $uri == 'guardar_firma_remoto':

        require './app/controllers/TicketController.php';

        guardarFirmaRemoto();

        break;


    case $uri == 'firma_ticket':

        require './app/controllers/TicketController.php';

        firmaTicket();

        break;


    case $uri == 'firma_exitosa':

        require './app/views/tickets/firma_exitosa.php';

        break;
    // case $uri == 'cerrar_ticket':

    //     require __DIR__ . '/../app/controllers/TicketController.php';

    //     cerrarTicket();

    //     break;


    // case $uri == 'solicitar_firma':

    //     require './app/controllers/TicketController.php';

    //     solicitarFirma();

    //     break;

    case preg_match(
        '/^pdf_ticket\/([0-9]+)$/',
        $uri,
        $matches
    ):

        $_GET["id"] = $matches[1];

        require __DIR__ . '/../app/controllers/TicketController.php';

        generarPDF();

        break;


    case $uri == 'logout':

        session_destroy();

        header('Location:/login');

        break;

    case $uri == 'tickets/nuevo':

        require __DIR__ . '/../app/controllers/TicketController.php';

        nuevoTicketPublico();

        break;


    case $uri == 'tickets/guardar':

        require __DIR__ . '/../app/controllers/TicketController.php';

        guardarTicketPublico();

        break;

    case $uri == 'tickets/consultar':

        require __DIR__ . '/../app/controllers/TicketController.php';

        consultarTicket();

        break;

    case 'eliminar_ticket':

        require __DIR__ . '/../app/controllers/TicketController.php';

        eliminarTicket();

        break;

    default:

        http_response_code(404);

        echo "404 página no encontrada";
}
