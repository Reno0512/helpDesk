<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<style>
    .consulta-ticket {
        margin-top: 30px;
    }

    .consulta-ticket .panel-body {
        padding: 30px;
    }

    .consulta-ticket .form-group {
        margin-bottom: 25px;
    }

    .consulta-ticket label {
        margin-bottom: 8px;
        font-weight: 600;
    }

    .consulta-ticket .form-control {
        height: 44px;
    }

    .consulta-ticket .btn {
        margin-right: 8px;
    }

    .consulta-ayuda {
        margin-bottom: 30px;
    }
</style>

<div class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default consulta-ticket">

                <div class="panel-heading">

                    <h3 class="panel-title">
                        Consultar Ticket
                    </h3>

                </div>

                <div class="panel-body">

                    <div class="alert alert-info consulta-ayuda">

                        <strong>Consulta el estado de tu solicitud</strong><br>

                        Ingresa el folio que recibiste al levantar
                        tu ticket para consultar su información y estado actual.

                    </div>

                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">
                            <span class="glyphicon glyphicon-exclamation-sign"></span>

                            <?php echo htmlspecialchars($error); ?>
                        </div>

                    <?php endif; ?>

                    <form
                        action="/tickets/consultar"
                        method="POST">

                        <div class="form-group">

                            <label for="folio">
                                Folio del Ticket
                            </label>

                            <input
                                type="text"
                                name="folio"
                                id="folio"
                                class="form-control"
                                maxlength="30"
                                required
                                placeholder="Ejemplo: TCK-2026-000125"
                                autocomplete="off"
                                value="<?php echo htmlspecialchars($_POST['folio'] ?? ''); ?>">

                            <p class="help-block">
                                Ingresa el folio exactamente como aparece
                                en el comprobante de tu ticket.
                            </p>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <span class="glyphicon glyphicon-search"></span>
                            Consultar Ticket

                        </button>

                        <a
                            href="/tickets/nuevo"
                            class="btn btn-secondary">

                            <span class="glyphicon glyphicon-plus"></span>
                            Levantar Ticket

                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include './app/views/layouts/firma.php'; ?>