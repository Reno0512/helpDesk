<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<style>

    .ticket-registrado {
        margin-top: 40px;
    }

    .ticket-registrado .panel-body {
        padding: 40px;
        text-align: center;
    }

    .registro-icono {
        font-size: 55px;
        margin-bottom: 20px;
    }

    .registro-titulo {
        margin-top: 0;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .registro-mensaje {
        color: #666;
        font-size: 16px;
        margin-bottom: 30px;
    }

    .folio-box {
        background: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 20px;
        margin: 25px auto 30px;
        max-width: 450px;
    }

    .folio-label {
        display: block;
        color: #777;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .folio {
        font-size: 28px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .datos-registro {
        max-width: 500px;
        margin: 0 auto 30px;
        text-align: left;
    }

    .dato {
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .dato:last-child {
        border-bottom: none;
    }

    .dato strong {
        display: inline-block;
        width: 150px;
        color: #555;
    }

    .botones-registro {
        margin-top: 25px;
    }

    .botones-registro .btn {
        margin: 5px;
    }

</style>


<div class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default ticket-registrado">

                <div class="panel-body">


                    <!-- ICONO -->

                    <div class="registro-icono">

                        <span class="glyphicon glyphicon-ok-circle text-success"></span>

                    </div>


                    <!-- TITULO -->

                    <h2 class="registro-titulo">

                        ¡Ticket registrado correctamente!

                    </h2>


                    <p class="registro-mensaje">

                        Tu solicitud fue recibida correctamente.
                        Guarda tu folio para consultar posteriormente
                        el estado de tu ticket.

                    </p>


                    <!-- FOLIO -->

                    <div class="folio-box">

                        <span class="folio-label">
                            Folio de tu ticket
                        </span>

                        <div class="folio">

                            <?php
                            echo htmlspecialchars($ticket['folio']);
                            ?>

                        </div>

                    </div>


                    <!-- DATOS -->

                    <div class="datos-registro">


                        <div class="dato">

                            <strong>
                                Área:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $ticket['nombre_area']
                            );
                            ?>

                        </div>


                        <div class="dato">

                            <strong>
                                Asunto:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $ticket['titulo']
                            );
                            ?>

                        </div>


                        <div class="dato">

                            <strong>
                                Prioridad:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $ticket['prioridad']
                            );
                            ?>

                        </div>


                        <div class="dato">

                            <strong>
                                Fecha:
                            </strong>

                            <?php
                            echo date(
                                'd/m/Y H:i',
                                strtotime($ticket['fecha'])
                            );
                            ?>

                        </div>


                    </div>


                    <!-- AVISO -->

                    <div class="alert alert-info">

                        <span class="glyphicon glyphicon-info-sign"></span>

                        <strong>Importante:</strong>

                        Guarda tu folio.
                        Lo necesitarás para consultar el estado
                        de tu solicitud.

                    </div>


                    <!-- BOTONES -->

                    <div class="botones-registro">

                        <a
                            href="/tickets/consultar"
                            class="btn btn-primary">

                            <span class="glyphicon glyphicon-search"></span>

                            Consultar Ticket

                        </a>


                        <a
                            href="/tickets/nuevo"
                            class="btn btn-secondary">

                            <span class="glyphicon glyphicon-plus"></span>

                            Levantar otro Ticket

                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>

    <?php include './app/views/layouts/firma.php'; ?>

</div>