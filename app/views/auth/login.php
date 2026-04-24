<!doctype html>
<html>

<head>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center align-items-center" style="height: 90vh;">

            <div class="col-md-4">

                <div class="card shadow-lg">

                    <div class="card-header bg-primary text-white">
                        <h4>SISTEMA DE TICKETS DE INFORMÁTICA</h4>
                    </div>

                    <div class="card-body">

                        <?php if (isset($error)) { ?>

                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>

                        <?php } ?>

                        <form method="POST">

                            <input
                                name="usuario"
                                class="form-control mb-3"
                                placeholder="Usuario">

                            <input
                                type="password"
                                name="password"
                                class="form-control mb-3"
                                placeholder="Contraseña">

                            <button class="btn btn-primary w-100">
                                Entrar
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <?php include './app/views/layouts/firma.php'; ?>

    </div>

</body>

</html>