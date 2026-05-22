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


switch (true) {

    case $uri == '':

    case $uri == 'login':
        require './app/controllers/AuthController.php';
        break;

    case $uri == 'dashboard':
        require __DIR__ . '/../app/controllers/DashboardController.php';
        break;

    case $uri == 'tickets':
        require __DIR__ . '/../app/controllers/TicketController.php';
        break;

    case $uri == 'ver_ticket/':
        require './app/controllers/TicketController.php';
        verTicket();
        break;


    case preg_match(
        '/^ticket\/([0-9]+)$/',
        $uri,
        $matches
    ):

        $_GET["id"] = $matches[1];

        require __DIR__ . '/../app/controllers/TicketController.php';

        break;



    case $uri == 'logout':

        session_destroy();

        header('Location:/login');

        break;

    default:

        http_response_code(404);

        echo "404 página no encontrada";
}
