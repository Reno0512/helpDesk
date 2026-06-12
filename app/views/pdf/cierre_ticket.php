<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0px;
        }

        html,
        body {
            margin: 0px;
            padding: 0px;
            height: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        .contenedor-fondo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            text-align: center;
        }

        .fondo {
            width: 100%;
            height: 100%;
            transform: scale(1);
            transform-origin: center center;
        }

        .contenido {
            padding-top: 120px;
            padding-left: 70px;
            padding-right: 40px;
            font-size: 14px;
            line-height: 1.5;
        }

        .encabezado {
            text-align: right;
            font-size: 15px;
            font-weight: bold;
        }

        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .datos-ticket {
            margin-bottom: 15px;
        }

        .tabla-firmas {
            position: absolute;
            bottom: 250px;
            left: 70px;
            right: 70px;
            width: 82%;
            border-collapse: collapse;
        }

        .col-firma {
            width: 50%;
            text-align: center;
            /* Alinea el contenido abajo para que la firma descanse sobre la línea */
            vertical-align: bottom;
            font-size: 13px;
        }

        /* Contenedor y estilo de la firma digital */
        .contenedor-firma-digital {
            height: 70px;
            /* Espacio reservado para la firma */
            margin-bottom: 15px;
            text-align: center;
        }

        .firma-digital {
            max-height: 110px;
            max-width: 220px;
            display: inline-block;
        }

        .linea-firma {
            width: 80%;
            margin: 0 auto;
            border-top: 1px solid #000000;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="contenedor-fondo">
        <img class="fondo" src="<?= $rutaImagen ?>">
    </div>

    <div class="contenido">
        <div class="encabezado">
            Área: Dirección de Informática <br>
            Taxco de Alarcón, Guerrero a <span><?php date_default_timezone_set('America/Mexico_City');
                                                $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                                echo date('j') . " de " . $meses[date('n')] . " de " . date('Y'); ?></span> <br>
            “2026, Año de Margarita Maza Parada” <br>
        </div>

        <div class="titulo">
            ACTA DE CIERRE DE TICKET
        </div>

        <div class="datos-ticket"><b>Folio:</b> <?= htmlspecialchars($ticket["folio"]) ?></div>
        <div class="datos-ticket"><b>Area:</b> <?= htmlspecialchars($ticket["area"]) ?></div>
        <div class="datos-ticket"><b>Título:</b> <?= htmlspecialchars($ticket["titulo"]) ?></div>
        <div class="datos-ticket"><b>Descripción:</b> <?= htmlspecialchars($ticket["descripcion"]) ?></div>
        <div class="datos-ticket"><b>Estatus:</b> <?= htmlspecialchars($ticket["estatus"]) ?></div>
        <div class="datos-ticket"><b>Fecha cierre:</b> <?= htmlspecialchars($ticket["fecha_cierre"]) ?></div>

        <br>
        <div class="datos-ticket">
            <b>Solución aplicada:</b><br>
            <?= nl2br(htmlspecialchars($ticket["solucion"])) ?>
        </div>

        <table class="tabla-firmas">
            <tr>
                <td class="col-firma">
                    <!-- Espacio dinámico para la firma del Técnico -->
                    <div class="contenedor-firma-digital">
                        <?php if (!empty($rutaFirma)): ?>
                            <img class="firma-digital" src="<?= $rutaFirma ?>" alt="Firma Digital">
                        <?php endif; ?>
                    </div>
                    <div class="linea-firma">Firma Técnico</div>
                </td>
                <td class="col-firma">
                    <!-- Espacio vacío para que el usuario firme a mano en el PDF impreso o digitalizado -->
                    <div class="contenedor-firma-digital">
                        <img
                            src="<?php echo $firma['firma_solicitante']; ?>"
                            style="width:200px;">
                    </div>
                    <div class="linea-firma">Firma Usuario</div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>