<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<div class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">
                        Levantar Ticket de Soporte
                    </h3>
                </div>

                <div class="panel-body">

                    <div class="alert alert-info ticket-publico-ayuda">
                        <strong>¿Necesitas ayuda?</strong><br>
                        Completa el siguiente formulario para reportar
                        tu solicitud. Al finalizar recibirás un folio 
                        para consultar el estado de tu ticket.
                    </div>

                    <form
                        action="/tickets/guardar"
                        method="POST"
                        enctype="multipart/form-data"
                        id="ticketPublicoForm">

                        <!-- Nombre -->

                        <div class="form-group">

                            <label for="reportante">
                                Nombre completo
                            </label>

                            <input
                                type="text"
                                name="reportante"
                                id="reportante"
                                class="form-control primera-mayuscula"
                                maxlength="200"
                                required
                                placeholder="Escribe tu nombre completo">

                        </div>


                        <!-- Teléfono -->

                        <div class="form-group">

                            <label for="telefono">
                                Teléfono
                            </label>

                            <input
                                type="tel"
                                name="telefono"
                                id="telefono"
                                class="form-control"
                                maxlength="30"
                                required
                                placeholder="Número de teléfono">

                        </div>


                        <!-- Área -->

                        <div class="form-group">

                            <label for="area_id">
                                Área / Dependencia
                            </label>

                            <select
                                name="area_id"
                                id="area_id"
                                class="form-control"
                                required>

                                <option value="" selected disabled>
                                    Selecciona un área
                                </option>

                                <?php while ($area = $areas->fetch_assoc()): ?>

                                    <option value="<?php echo $area['id_area']; ?>">

                                        <?php echo htmlspecialchars($area['nombre']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <!-- Título -->

                        <div class="form-group">

                            <label for="titulo">
                                Asunto
                            </label>

                            <input
                                type="text"
                                name="titulo"
                                id="titulo"
                                class="form-control primera-mayuscula"
                                maxlength="200"
                                required
                                placeholder="Describe brevemente el problema">

                        </div>


                        <!-- Descripción -->

                        <div class="form-group">

                            <label for="descripcion">
                                Descripción del problema
                            </label>

                            <textarea
                                name="descripcion"
                                id="descripcion"
                                class="form-control primera-mayuscula"
                                rows="5"
                                required
                                placeholder="Describe detalladamente el problema"></textarea>

                        </div>


                        <!-- Evidencia -->

                        <div class="form-group">

                            <label for="evidencia">
                                Evidencia fotográfica
                            </label>

                            <input
                                type="file"
                                name="evidencia"
                                id="evidencia"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp">

                            <p class="help-block">
                                Puedes adjuntar una imagen que ayude a identificar
                                el problema.
                            </p>

                        </div>


                        <!-- Botón -->

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Levantar Ticket

                        </button>

                        <a
                            href="/tickets/consultar"
                            class="btn btn-secondary">

                            Consultar Ticket

                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php include './app/views/layouts/firma.php'; ?>

</div>


<style>
    #ticketPublicoForm .form-group {
        margin-bottom: 10px;
    }

    #ticketPublicoForm label {
        margin-bottom: 8px;
        font-weight: 600;
    }

    #ticketPublicoForm .form-control {
        height: 42px;
    }

    #ticketPublicoForm textarea.form-control {
        height: auto;
        resize: vertical;
    }

    #ticketPublicoForm .help-block {
        margin-top: 5px;
    }

    #ticketPublicoForm .btn {
        margin-top: 5px;
        margin-right: 8px;
    }

    .ticket-publico-ayuda {
        margin-bottom: 15px;
    }
</style>