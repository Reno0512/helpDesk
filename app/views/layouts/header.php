<?php
    // Define la URL base de tu sitio web
    define('BASE_URL', 'https://sti.taxco.gob.mx/');
?>
<!doctype html>
<html>

<head>

    <title>Tickets Informatica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <img src="<?php echo BASE_URL; ?>/assets/img/logoBiselSombraH.png" alt="logoTaxco" class="img-fluid ms-3" style="max-height: 50px;">
            <a class="navbar-brand">
                Sistema de tickets
            </a>
            <a href="/logout" class="btn btn-danger">Salir</a>
        </div>

    </nav>

    <div>