<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<div class="container-fluid">

    <h3 class="mb-4">
        Ticket:
        <?php echo $ticket["folio"]; ?>
    </h3>


    <div class="row">

        <!-- INFORMACION TICKET -->
        <div class="col-md-6">

            <div class="card shadow mb-4">

                <div class="card-header bg-primary text-white">
                    Información Ticket
                </div>

                <div class="card-body">

                    <h4>
                        <?php echo $ticket["titulo"]; ?>
                    </h4>

                    <hr>

                    <p>
                        <?php echo $ticket["descripcion"]; ?>
                    </p>

                    <p>
                        <b>Prioridad:</b>

                        <?php
                        switch ($ticket["prioridad"]) {

                            case 'Alta':
                                echo '<span class="badge bg-danger">Alta</span>';
                                break;

                            case 'Media':
                                echo '<span class="badge bg-warning">Media</span>';
                                break;

                            default:
                                echo '<span class="badge bg-success">Baja</span>';
                        }
                        ?>
                    </p>


                    <p>
                        <b>Estatus:</b>

                        <?php
                        switch ($ticket["estatus"]) {

                            case 'En proceso':
                                echo '<span class="badge bg-warning">En proceso</span>';
                                break;

                            case 'Cerrado':
                                echo '<span class="badge bg-success">Cerrado</span>';
                                break;

                            default:
                                echo '<span class="badge bg-secondary">Nuevo</span>';
                        }
                        ?>
                    </p>

                </div>
            </div>

        </div>



        <!-- ACTUALIZAR -->
        <div class="col-md-6">

            <div class="card shadow mb-4">

                <div class="card-header bg-dark text-white">
                    Seguimiento
                </div>

                <div class="card-body">

                    <form
                        action="/actualizar_ticket"
                        method="POST">

                        <input
                            type="hidden"
                            name="ticket_id"
                            value="<?php echo $ticket['id']; ?>">


                        <div class="mb-3">

                            <label>
                                Cambiar Estatus
                            </label>

                            <select
                                id="estatus"
                                name="estatus"
                                class="form-control">

                                <option value="Nuevo">Nuevo</option>
                                <option value="En proceso">En proceso</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Cerrado">Cerrado</option>

                            </select>

                        </div>



                        <div class="mb-3">

                            <label>
                                Comentario Técnico
                            </label>

                            <textarea
                                name="comentario"
                                class="form-control"
                                rows="2"></textarea>

                        </div>

                        <div id="panelCierre" style="display:none;">

                            <div class="mb-3">

                                <label>
                                    Solución Aplicada
                                </label>

                                <textarea
                                    name="solucion"
                                    class="form-control"
                                    rows="2"></textarea>

                            </div>


                            <div class="mb-3">

                                <label>
                                    Observaciones
                                </label>

                                <textarea
                                    name="observaciones"
                                    class="form-control"
                                    rows="2"></textarea>

                            </div>

                        </div>

                        <button
                            id="btnSeguimiento"
                            type="submit"
                            class="btn btn-primary">

                            Guardar Seguimiento

                        </button>

                        <button
                            id="btnCerrar"
                            type="submit"
                            formaction="/cerrar_ticket"
                            class="btn btn-danger"
                            style="display:none;">

                            Cerrar Ticket

                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script>
    // $("#estatus").change(function() {

    //     if ($(this).val() == "Cerrado") {

    //         $("#btnSeguimiento").hide();
    //         $("#btnCerrar").show();

    //     } else {

    //         $("#btnSeguimiento").show();
    //         $("#btnCerrar").hide();

    //     }

    // });

    $(document).ready(function() {

        $("#estatus").change(function() {

            let estatus = $(this).val();

            if (estatus == "Cerrado") {

                $("#panelCierre").show();

                $("#btnSeguimiento").hide();

                $("#btnCerrar").show();

            } else {

                $("#panelCierre").hide();

                $("#btnSeguimiento").show();

                $("#btnCerrar").hide();

            }

        });

    });
</script>