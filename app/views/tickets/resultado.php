<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<style>
    .resultado-ticket {
        margin-top: 30px;
    }

    .resultado-ticket .panel-body {
        padding: 30px;
    }

    .ticket-folio {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .ticket-dato {
        margin-bottom: 20px;
    }

    .ticket-dato label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #555;
    }

    .ticket-dato .valor {
        font-size: 15px;
    }

    .ticket-descripcion {
        background: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 15px;
        min-height: 80px;
        /* white-space: pre-line; */
    }

    .historial {
        margin-top: 35px;
    }

    .historial h4 {
        margin-bottom: 25px;
    }

    .timeline {
        position: relative;
        margin-left: 15px;
        padding-left: 30px;
        border-left: 2px solid #ddd;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item:before {
        content: "";
        position: absolute;
        left: -39px;
        top: 3px;
        width: 16px;
        height: 16px;
        background: #fff;
        border: 3px solid #337ab7;
        border-radius: 50%;
    }

    .timeline-fecha {
        font-size: 12px;
        color: #888;
        margin-bottom: 5px;
    }

    .timeline-estatus {
        font-weight: bold;
        margin-bottom: 7px;
    }

    .timeline-comentario {
        background: #f7f7f7;
        padding: 12px 15px;
        border-radius: 4px;
        border: 1px solid #e5e5e5;
    }
</style>


<div class="container">

    <div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default resultado-ticket">

                <div class="panel-heading">

                    <h3 class="panel-title">
                        Consulta de Ticket
                    </h3>

                </div>


                <div class="panel-body">


                    <!-- FOLIO -->

                    <div class="ticket-folio">

                        Folio:
                        <?php echo htmlspecialchars($ticket['folio']); ?>

                    </div>


                    <!-- DATOS -->

                    <div class="row">

                        <div class="col-md-6">

                            <div class="ticket-dato">

                                <label>
                                    Estado
                                </label>

                                <div class="valor">

                                    <span class="label label-primary">

                                        <?php
                                        echo htmlspecialchars(
                                            $ticket['nombre_estatus']
                                        );
                                        ?>

                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="ticket-dato">

                                <label>
                                    Área / Dependencia
                                </label>

                                <div class="valor">

                                    <?php
                                    echo htmlspecialchars(
                                        $ticket['nombre_area']
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="ticket-dato">

                                <label>
                                    Prioridad
                                </label>

                                <div class="valor">

                                    <?php
                                    echo htmlspecialchars(
                                        $ticket['prioridad']
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="ticket-dato">

                                <label>
                                    Fecha de solicitud
                                </label>

                                <div class="valor">

                                    <?php
                                    echo date(
                                        'd/m/Y H:i',
                                        strtotime($ticket['fecha'])
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="ticket-dato">

                                <label>
                                    Asunto
                                </label>

                                <div class="valor">

                                    <?php
                                    echo htmlspecialchars(
                                        $ticket['titulo']
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="ticket-dato">

                                <label>
                                    Descripción
                                </label>

                                <div class="ticket-descripcion">

                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $ticket['descripcion']
                                        )
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- HISTORIAL -->

                    <div class="historial">

                        <h4>
                            Historial de atención
                        </h4>


                        <?php if ($historial && $historial->num_rows > 0): ?>

                            <div class="timeline">

                                <?php while ($item = $historial->fetch_assoc()): ?>

                                    <div class="timeline-item">

                                        <div class="timeline-fecha">

                                            <?php
                                            echo date(
                                                'd/m/Y H:i',
                                                strtotime($item['fecha'])
                                            );
                                            ?>

                                        </div>


                                        <div class="timeline-estatus">

                                            <?php
                                            echo htmlspecialchars(
                                                $item['nombre_estatus']
                                            );
                                            ?>

                                        </div>


                                        <?php if (!empty($item['comentario'])): ?>

                                            <div class="timeline-comentario">

                                                <?php
                                                echo nl2br(
                                                    htmlspecialchars(
                                                        $item['comentario']
                                                    )
                                                );
                                                ?>

                                            </div>

                                        <?php endif; ?>

                                    </div>

                                <?php endwhile; ?>

                            </div>

                        <?php else: ?>

                            <div class="alert alert-info">

                                Este ticket todavía no tiene movimientos
                                registrados.

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- BOTONES -->

                    <hr>

                    <a
                        href="/tickets/consultar"
                        class="btn btn-primary">

                        <span class="glyphicon glyphicon-search"></span>

                        Consultar otro ticket

                    </a>


                    <a
                        href="/tickets/nuevo"
                        class="btn btn-secondary">

                        <span class="glyphicon glyphicon-plus"></span>

                        Levantar nuevo ticket

                    </a>


                </div>

            </div>

        </div>

    </div>

    <?php include './app/views/layouts/firma.php'; ?>

</div>