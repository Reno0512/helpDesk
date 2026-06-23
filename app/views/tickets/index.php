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
                <th>Técnico</th>
            <?php endif; ?>
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

                <?php if ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "tecnico"): ?>
                    <td>
                        <?php echo $t['tecnico']; ?>
                    </td>
                <?php endif; ?>

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
                            class="form-control primera-mayuscula"
                            placeholder="Nombre de quien reporta">
                    </div>

                    <div class="mb-3">
                        <label>Titulo</label>
                        <input
                            name="titulo"
                            class="form-control primera-mayuscula"
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
                        <textarea name="descripcion" class="form-control primera-mayuscula" placeholder="Describe tu problema con detalle anexando folio(s) de equipo(s)"></textarea>
                    </div>

                    <div class="mb-3">

                        <label>Evidencia fotográfica</label>

                        <div class="d-flex gap-2 mb-2">

                            <button
                                type="button"
                                class="btn btn-primary"
                                onclick="document.getElementById('camaraInput').click()">
                                📷 Tomar Foto
                            </button>

                            <button
                                type="button"
                                class="btn btn-success"
                                onclick="document.getElementById('galeriaInput').click()">
                                🖼️ Elegir de Galería
                            </button>

                        </div>

                        <!-- Cámara -->
                        <input
                            type="file"
                            id="camaraInput"
                            accept="image/*"
                            capture="environment"
                            style="display:none;">

                        <!-- Galería -->
                        <input
                            type="file"
                            id="galeriaInput"
                            accept="image/*"
                            style="display:none;">

                        <!-- Este es el que realmente se enviará -->
                        <input
                            type="file"
                            id="evidencia"
                            name="evidencia"
                            style="display:none;">

                        <small class="text-muted">
                            Puedes tomar una foto o seleccionar una imagen existente.
                        </small>

                        <div class="mt-2 text-center">
                            <img
                                id="previewImagen"
                                src=""
                                class="img-fluid rounded border d-none"
                                style="max-height:250px;">
                        </div>

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
        "order": [],

        language: {
            url: 'assets/datatables/es-ES.json',
        },
    });

    $("#guardar").click(function() {

        let formData = new FormData(
            document.getElementById("ticketForm")
        );

        $.ajax({

            url: "api/tickets.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function() {

                Swal.fire({
                    title: 'Creando ticket...',
                    text: 'Espere un momento',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

            },

            success: function(folio) {

                Swal.fire({
                    title: 'Ticket registrado',
                    text: 'Folio ' + folio,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {

                    if (result.isConfirmed) {

                        $("#ticketForm")[0].reset();

                        const modal =
                            bootstrap.Modal.getInstance(
                                document.getElementById(
                                    'modalTicket'
                                )
                            );

                        modal.hide();

                        location.reload();
                    }

                });

            },

            error: function(xhr) {

                Swal.fire({
                    title: 'Error',
                    text: 'No fue posible crear el ticket',
                    icon: 'error'
                });

                console.log(xhr.responseText);

            }

        });

    });

    function procesarImagen(input) {

        if (!input.files || !input.files[0]) {
            return;
        }

        const archivo = input.files[0];

        // Mostrar preview
        const reader = new FileReader();

        reader.onload = function(e) {

            $("#previewImagen")
                .attr("src", e.target.result)
                .removeClass("d-none");

        };

        reader.readAsDataURL(archivo);

        // Copiar archivo al input que se envía
        const dt = new DataTransfer();
        dt.items.add(archivo);

        document.getElementById("evidencia").files = dt.files;
    }

    $("#camaraInput").on("change", function() {
        procesarImagen(this);
    });

    $("#galeriaInput").on("change", function() {
        procesarImagen(this);
    });

    $('.primera-mayuscula').on('input', function() {
        let texto = $(this).val();
        if (texto.length > 0) {
            // Convierte solo el primer carácter a mayúscula
            $(this).val(texto.charAt(0).toUpperCase() + texto.slice(1));
        }
    });
</script>