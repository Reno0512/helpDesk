<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>


<?php if ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "tecnico") : ?>
    <div class="row">

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">

                    <h3>
                        <?php echo $abiertos; ?>
                    </h3>

                    Tickets abiertos

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h3>
                        <?php echo $enProceso; ?>
                    </h3>

                    Tickets En Proceso

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h3>
                        <?php echo $pendientes; ?>
                    </h3>

                    Tickets Pendientes

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h3>
                        <?php echo $cerrados; ?>
                    </h3>

                    Tickets Cerrados

                </div>
            </div>
        </div>

    </div>

    <hr>

    <a href="/tickets"
        class="btn btn-primary">
        Ver tickets
    </a>
<?php endif; ?>

</div>
</body>

</html>