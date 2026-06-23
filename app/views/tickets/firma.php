<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma de Ticket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <style>
        body {
            background: #f5f7fa;
        }

        .card-firma {
            max-width: 900px;
            margin: 30px auto;
        }

        canvas {
            width: 100%;
            height: 250px;
            border: 2px dashed #ced4da;
            border-radius: 8px;
            background: #fff;
            touch-action: none;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card shadow card-firma">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Firma de Conformidad
                </h4>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label class="fw-bold">
                        Ticket:
                    </label>
                    <?= $ticket["folio"] ?>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">
                        Título:
                    </label>
                    <br>
                    <?= $ticket["titulo"] ?>
                </div>

                <div class="mb-4">
                    <label class="fw-bold">
                        Descripción:
                    </label>
                    <br>
                    <?= nl2br($ticket["descripcion"]) ?>
                </div>

                <div class="mb-4">
                    <label class="fw-bold">
                        Solución:
                    </label>
                    <br>
                    <?= nl2br($ticket["solucion"]) ?>
                </div>

                <div class="mb-4">
                    <label class="fw-bold">
                        Observaciones :
                    </label>
                    <br>
                    <?= nl2br($ticket["observaciones"]) ?>
                </div>

                <input
                    type="hidden"
                    id="ticket_id"
                    value="<?= $ticket["id"] ?>">

                <label class="fw-bold mb-2">
                    Firma del solicitante
                </label>

                <canvas id="firmaSolicitante"></canvas>

                <div class="mt-3 text-center">

                    <button
                        type="button"
                        id="limpiarFirma"
                        class="btn btn-secondary">

                        Limpiar

                    </button>

                    <button
                        type="button"
                        id="guardarFirma"
                        class="btn btn-success">

                        Firmar y Cerrar Ticket

                    </button>

                </div>

                <div
                    id="mensaje"
                    class="mt-3 text-center">
                </div>

            </div>

        </div>

    </div>

    <script>
        const canvas =
            document.getElementById("firmaSolicitante");

        function ajustarCanvas() {

            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = 250 * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            firma.clear();
        }

        let firma = new SignaturePad(canvas);

        ajustarCanvas();

        window.addEventListener("resize", ajustarCanvas);


        $("#limpiarFirma").click(function() {

            firma.clear();

        });

        $("#guardarFirma").click(function() {

            if (firma.isEmpty()) {

                alert(
                    "Debe capturar su firma."
                );

                return;
            }

            $.ajax({

                url: '/guardar_firma_remoto',

                method: 'POST',

                data: {

                    ticket_id: $("#ticket_id").val(),

                    firma: firma.toDataURL()

                },


                success: function(response) {

                    alert(
                        "Firma guardada correctamente."
                    );

                    window.location.href =
                        "/firma_exitosa";


                    // try {

                    //     let data =
                    //         JSON.parse(response);



                    //     if (data.ok) {

                    //         alert(
                    //             "Firma guardada correctamente."
                    //         );

                    //         window.location.href =
                    //             "/firma_exitosa";

                    //     }

                    // } catch (e) {
                    //     alert(
                    //         "Error al guardar la firma."
                    //     );

                    // }

                },

                error: function() {

                    alert(
                        "Error de comunicación con el servidor."
                    );

                }

            });

        });

        // document.getElementById("guardarFirma").addEventListener(
        //     "click",
        //     function() {
        //         if (
        //             firma.isEmpty()
        //         ) {

        //             alert(
        //                 "Debe firmar antes de continuar."
        //             );

        //             return;
        //         }

        //         const firmaBase64 = firma.toDataURL("image/png");

        //         const ticketId = document.getElementById("ticket_id").value;

        //         fetch(
        //                 "index.php?action=guardarFirmaSolicitante", {
        //                     method: "POST",
        //                     headers: {
        //                         "Content-Type": "application/json"
        //                     },
        //                     body: JSON.stringify({
        //                         ticket_id: ticketId,
        //                         firma: firmaBase64
        //                     })
        //                 }
        //             )
        //             .then(
        //                 response => response.json()
        //             )
        //             .then(
        //                 data => {

        //                     if (data.success) {

        //                         document
        //                             .getElementById(
        //                                 "mensaje"
        //                             )
        //                             .innerHTML =
        //                             '<div class="alert alert-success">Firma guardada correctamente.</div>';

        //                         document
        //                             .getElementById(
        //                                 "guardarFirma"
        //                             )
        //                             .disabled = true;

        //                     } else {

        //                         document
        //                             .getElementById(
        //                                 "mensaje"
        //                             )
        //                             .innerHTML =
        //                             '<div class="alert alert-danger">' +
        //                             data.message +
        //                             '</div>';

        //                     }

        //                 }
        //             );

        //     }
        // );
    </script>

</body>

</html>