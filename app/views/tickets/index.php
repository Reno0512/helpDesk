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
    class="table table-bordered">

    <thead>
        <tr>
            <th>Folio</th>
            <th>Titulo</th>
            <th>Prioridad</th>
            <th>Estatus</th>
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
                    <?php echo $t['prioridad']; ?>
                </td>

                <td>
                    <?php echo $t['estatus']; ?>
                </td>

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

            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    Levantar Falla
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <form id="ticketForm">

                    <div class="mb-3">
                        <label>Titulo</label>
                        <input
                            name="titulo"
                            class="form-control">
                    </div>


                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea
                            name="descripcion"
                            class="form-control">
                        </textarea>
                    </div>


                    <div class="mb-3">
                        <label>Prioridad</label>

                        <select
                            name="prioridad"
                            class="form-select">

                            <option>Baja</option>
                            <option>Media</option>
                            <option>Alta</option>

                        </select>

                    </div>

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

<script>
    $('#tabla').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/{plugins-release-version}/i18n/es-ES.json',
        },
    });
</script>