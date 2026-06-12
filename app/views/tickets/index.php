<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<h3>Tickets</h3>

<button
    class="btn btn-success mb-3"
    data-bs-toggle="modal"
    data-bs-target="#modalTicket">
    Nuevo Ticket
</button>

<table id="tabla"
    class="table table-bordered" style="width:100%">

    <thead>
        <tr>
            <th>Folio</th>
            <th>Titulo</th>
            <th>Área</th>
            <th>Prioridad</th>
            <th>Estatus</th>
            <?php if ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "tecnico"): ?>
                <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>

    <tbody>

        <?php while ($t = $tickets->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo $t['folio']; ?>
                </td>

                <td>
                    <?php echo $t['titulo']; ?>
                </td>

                <td>
                    <?php echo $t['nombre_area']; ?>
                </td>

                <td>
                    <?php echo $t['prioridad']; ?>
                </td>

                <td>
                    <?php
                    
                        switch ($t["nombre_estatus"]) {

                            case 'En proceso':
                                echo '<span class="badge bg-warning">En proceso</span>';
                                break;

                            case 'Cerrado':
                                echo '<span class="badge bg-danger">Cerrado</span>';
                                break;

                            case 'Pendiente':
                                echo '<span class="badge bg-info">Pendiente</span>';
                                break;

                            default:
                                echo '<span class="badge bg-secondary">Nuevo</span>';
                        }
                        ?>
                    <!-- <?php echo $t['nombre_estatus']; ?> -->

                </td>

                <?php if ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "tecnico") : ?>
                    <td class="text-center">

                        <a
                            href="/ver_ticket/<?php echo $t['id']; ?>"
                            class="btn btn-info btn-sm">
                            Ver
                        </a>

                        <!-- <a
                            href="?url=editar_ticket&id=<?php echo $t['id']; ?>"
                            class="btn btn-warning btn-sm">
                            Editar
                        </a>


                        <button
                            onclick="eliminar(<?php echo $t['id']; ?>)"
                            class="btn btn-danger btn-sm">
                            Eliminar
                        </button> -->

                    </td>
                <?php endif; ?>

            </tr>

        <?php } ?>

    </tbody>
</table>

<div class="modal fade"
    id="modalTicket"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    Levantar Falla
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <form id="ticketForm">

                    <div class="mb-3">
                        <label>Reportante</label>
                        <input
                            name="reportante"
                            class="form-control"
                            placeholder="Nombre de quien reporta">
                    </div>

                    <div class="mb-3">
                        <label>Titulo</label>
                        <input
                            name="titulo"
                            class="form-control"
                            placeholder="Titulo o asunto principal del problema">
                    </div>

                    <div class="mb-3">

                        <label>Área General:</label>
                        <select id="area" class="form-control" name="area" onchange="cargarSubareas(this.value)" required>
                            <option value="" selected disabled>Selecciona una Dirección General</option>
                            <?php foreach ($areas_principales as $area): ?>
                                <option value="<?php echo $area['id_area']; ?>">
                                    <?php echo htmlspecialchars($area['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" placeholder="Describe tu problema con detalle anexando folio(s) de equipo(s)"></textarea>
                    </div>

                    <input type="hidden" id="usuario_id" name="usuario_id" value=<?= $_SESSION["id"] ?>>
                    <input type="hidden" id="rol" name="rol" value=<?= $_SESSION["rol"] ?>>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-dismiss="modal">
                    Cerrar
                </button>

                <button
                    id="guardar"
                    type="button"
                    class="btn btn-success">
                    Guardar
                </button>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#tabla').DataTable({
        "scrollX": true,
        
        language: {
            url: 'assets/datatables/es-ES.json',
        },
    });

    $("#guardar").click(function() {

        $.ajax({

            url: "api/tickets.php",
            type: "POST",
            data: $("#ticketForm").serialize(),

            success: function(folio) {

                // Swal.fire(
                //     'Correcto',
                //     'Ticket registrado',
                //     'success'
                // );

                Swal.fire({
                    title: 'Ticket registrado',
                    text: 'Se generó correctamente',
                    text: 'Folio ' + folio,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false

                }).then((result) => {

                    if (result.isConfirmed) {

                        $("#ticketForm")[0].reset();
                        $("#modalTicket").modal('hide');

                        location.reload();
                    }

                });

            }

        });

    });
</script>