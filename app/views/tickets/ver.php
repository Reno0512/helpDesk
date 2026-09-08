<?php include 'app/views/layouts/header.php'; ?>
<?php include 'app/views/layouts/sidebar.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<div class="container-fluid">

	<h3 class="mb-4">
		Ticket:
		<?php echo $ticket["folio"]; ?>
	</h3>

	<?php if (isset($_SESSION['mensaje'])): ?>

		<div class="alert alert-<?php echo $_SESSION['tipo_mensaje']; ?> alert-dismissible fade show mt-3">

			<?php echo $_SESSION['mensaje']; ?>

			<button
				type="button"
				class="btn-close"
				data-bs-dismiss="alert">
			</button>

		</div>

		<?php
		unset($_SESSION['mensaje']);
		unset($_SESSION['tipo_mensaje']);
		?>

	<?php endif; ?>

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
						<b>Descripción:</b>
						<?php echo $ticket["descripcion"]; ?>
					</p>

					<p>
						<b>Reportante:</b>
						<?php echo $ticket["reportante"]; ?>
					</p>

					<p>
						<b>Area:</b>
						<?php echo $ticket["nombre_area"]; ?>
					</p>

					<p>
						<b>Fecha Reporte:</b>
						<?php echo $ticket["fecha"]; ?>
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
						switch ($ticket["nombre_estatus"]) {

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
					</p>

					<?php if (!empty($ticket["evidencia"])): ?>

						<p class="mt-3">

							<b>Evidencia:</b>

							<br>

							<img
								id="imagenGrande"
								src="../uploads/tickets/<?php echo $ticket["evidencia"]; ?>"
								class="img-fluid rounded border"
								style="max-height:250px; cursor:pointer;"
								onclick="verImagen(this.src)">

						</p>

					<?php endif; ?>

				</div>
			</div>
		</div>


		<div class="col-md-6">

			<div class="card shadow mb-4">

				<div class="card-header bg-dark text-white">
					Seguimiento
				</div>

				<div class="card-body">

					<form
						action="/actualizar_ticket"
						method="POST"
						id="segForm"
						enctype="multipart/form-data">

						<input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $ticket['id']; ?>">

						<div class="mb-3">

							<label>
								Cambiar Estatus
							</label>

							<select id="estatus" class="form-control" name="estatus" required>
								<option value="" disabled>Selecciona un Estatus</option>

								<?php foreach ($estatus_cat as $status): ?>
									<option value="<?php echo $status['id_estatus']; ?>" <?php echo ($status['id_estatus'] == $ticket['estatus_id']) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($status['nombre']); ?>
									</option>
								<?php endforeach; ?>
							</select>

						</div>

						<div class="mb-3">

							<label>
								Cambiar Prioridad
							</label>

							<select id="prioridad" class="form-control" name="prioridad" required>
								<option value="" disabled>Selecciona una Prioridad</option>

								<option value="<?php echo 'Baja' ?>" <?php echo ('Baja' == $ticket['prioridad']) ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars('Baja'); ?>
								</option>

								<option value="<?php echo 'Media' ?>" <?php echo ('Media' == $ticket['prioridad']) ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars('Media'); ?>
								</option>

								<option value="<?php echo 'Alta' ?>" <?php echo ('Alta' == $ticket['prioridad']) ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars('Alta'); ?>
								</option>

							</select>

						</div>

						<div class="mb-3">

							<label>
								Asignar Técnico
							</label>

							<select
								id="tecnico"
								class="form-control"
								name="tecnico_id"
								required>

								<option
									value=""
									disabled
									<?php echo empty($ticket['tecnico_id']) ? 'selected' : ''; ?>>

									Selecciona un Técnico

								</option>

								<?php foreach ($tecnicos as $tecnico): ?>

									<option
										value="<?php echo $tecnico['id']; ?>"
										<?php echo ($tecnico['id'] == $ticket['tecnico_id']) ? 'selected' : ''; ?>>

										<?php echo htmlspecialchars($tecnico['nombre']); ?>

									</option>

								<?php endforeach; ?>

							</select>

						</div>

						<div class="mb-3">

							<label>
								Comentario Técnico
							</label>

							<textarea
								name="comentario"
								id="comentario"
								class="form-control primera-mayuscula"
								rows="2"></textarea>

						</div>

						<div class="mb-3">

							<label>
								Evidencia Fotográfica
							</label>

							<input
								type="file"
								name="imagen"
								id="imagen"
								class="form-control"
								accept="image/*">

							<small class="text-muted">
								Selecciona una imagen.
							</small>

						</div>

						<div id="panelCierre" style="display:none;">

							<div class="mb-3">

								<label>
									Solución Aplicada
								</label>

								<textarea
									name="solucion"
									id="solucion"
									class="form-control primera-mayuscula"
									rows="2"></textarea>

							</div>


							<div class="mb-3">

								<label>
									Observaciones
								</label>

								<textarea
									name="observaciones"
									id="observaciones"
									class="form-control primera-mayuscula"
									rows="2"></textarea>

							</div>

						</div>

						<button
							id="btnSeguimiento"
							type="submit"
							class="btn btn-primary mb-3">

							Guardar Seguimiento

						</button>

						<button
							id="btnFirmar"
							type="button"
							class="btn btn-danger mb-3"
							data-bs-toggle="modal"
							data-bs-target="#modalFirma"
							style="display:none;">

							Firmar Ticket

						</button>

						<button
							id="btnSolicitarFirma"
							type="button"
							class="btn btn-success mb-3"
							style="display:none;">

							Solicitar Firma

						</button>

						<button
							id="btnWhatsApp"
							type="button"
							class="btn btn-success mb-3"
							onclick="enviarWhatsApp()">

							<i class="fa fa-whatsapp"></i>
							Enviar WhatsApp

						</button>

						<?php if ($ticket["nombre_estatus"] == "Cerrado") { ?>
							<a
								href="https://sti.taxco.gob.mx/pdf_ticket/<?php echo $ticket["id"]; ?>"
								target="_blank"
								class="btn btn-danger mb-3">

								Ver PDF de Cierre

							</a>

						<?php } ?>

						<!-- <button
                            id="btnCerrar"
                            type="submit"
                            formaction="/cerrar_ticket"
                            class="btn btn-danger"
                            style="display:none;">

                            Cerrar Ticket

                        </button> -->

					</form>

				</div>
			</div>
		</div>


		<div class="card mt-4">

			<div class="card-header">
				Historial
			</div>

			<div class="card-body" style="max-height: 250px; overflow-y: auto;">

				<?php while ($h = $historial->fetch_assoc()) { ?>

					<div class="border-start border-primary ps-3 mb-4">

						<h6>
							<?php echo $h["nombre_estatus"]; ?>
						</h6>

						<p>
							<?php echo nl2br($h["comentario"]); ?>
						</p>

						<?php if (!empty($h["imagen"])) { ?>

							<div class="mt-2">

								<a
									href="<?php echo $h["imagen"]; ?>"
									target="_blank">

									<img
										src="<?php echo $h["imagen"]; ?>"
										class="img-thumbnail"
										style="max-width:200px;">

								</a>

							</div>

						<?php } ?>

						<small>

							<?php echo $h["usuario"]; ?>

							|

							<?php echo $h["fecha"]; ?>

						</small>

					</div>

				<?php } ?>

			</div>

		</div>

	</div>

</div>

<div class="modal fade"
	tabindex="-1"
	id="modalFirma">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header bg-dark text-white">

				<h5>
					Firma de conformidad
				</h5>

				<!-- <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button> -->

				<button
					type="button"
					class="btn-close btn-close-white"
					data-bs-dismiss="modal">
				</button>

			</div>

			<div class="modal-body">

				<canvas
					id="firmaSolicitante"
					style="
                        border:1px solid #ccc;
                        width:100%;
                        height:250px;">
				</canvas>

			</div>

			<div class="modal-footer justify-content-center">

				<button
					type="button"
					id="btnLimpiarFirma"
					class="btn btn-success">

					Limpiar Firma

				</button>

				<button
					type="button"
					class="btn btn-secondary"
					data-bs-dismiss="modal">

					Cancelar

				</button>

				<button
					type="button"
					id="guardarFirma"
					class="btn btn-danger">

					Firmar y Cerrar Ticket

				</button>

			</div>

		</div>

	</div>

</div>

<div class="modal fade"
	id="modalImagen"
	tabindex="-1"
	aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-centered">

		<div class="modal-content">

			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Evidencia
				</h5>

				<button
					type="button"
					class="btn-close btn-close-white"
					data-bs-dismiss="modal">
				</button>

			</div>

			<div class="modal-body text-center">

				<img
					id="imagenGrande"
					src=""
					class="img-fluid"
					style="max-height:450px;">

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="modalLink">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header  bg-dark text-white">
				<h5>Enlace de Firma</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
				</button>
			</div>
			<div class="modal-body">
				<input id="linkFirma" class="form-control" readonly>
			</div>
			<div class="modal-footer">
				<button id="btnCopiar" class="btn btn-primary" onclick="copiarLink()">
					<span id="textoBoton">Copiar</span>
				</button>
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

			let estatus = $(this).find('option:selected').text().trim();

			// console.log(estatus);

			if (estatus == "Cerrado") {

				$("#panelCierre").show();

				$("#btnSeguimiento").hide();

				$("#btnFirmar").show();

				$("#btnSolicitarFirma").show();


			} else {

				$("#panelCierre").hide();

				$("#btnSeguimiento").show();

				$("#btnFirmar").hide();

				$("#btnSolicitarFirma").hide();

			}

		});

		setTimeout(function() {

			$(".alert").fadeOut(
				500,
				function() {
					$(this).remove();
				}
			);

		}, 3000);

	});

	let firma;

	$('#modalFirma').on('shown.bs.modal', function() {

		const canvas =
			document.getElementById("firmaSolicitante");

		canvas.width =
			canvas.offsetWidth;

		canvas.height = 250;

		firma =
			new SignaturePad(canvas);

	});

	$(document).on('click', '#btnLimpiarFirma', function() {

		if (firma) {

			firma.clear();

		}

	});


	$("#guardarFirma").click(function() {

		if (firma.isEmpty()) {

			alert(
				"Debe capturar la firma."
			);

			return;
		}


		/*
		Validar evidencia fotográfica
		*/

		const inputImagen =
			document.getElementById("imagen");


		if (
			!inputImagen.files ||
			inputImagen.files.length === 0
		) {

			alert(
				"Debe seleccionar una evidencia fotográfica."
			);

			return;

		}


		/*
		Crear FormData
		*/

		let formData =
			new FormData();


		/*
		Datos del ticket
		*/

		formData.append(
			"ticket_id",
			$("#ticket_id").val()
		);


		formData.append(
			"comentario",
			$("#comentario").val()
		);


		formData.append(
			"solucion",
			$("#solucion").val()
		);


		formData.append(
			"observaciones",
			$("#observaciones").val()
		);


		/*
		Firma digital
		*/

		formData.append(
			"firma",
			firma.toDataURL()
		);


		/*
		Evidencia fotográfica
		*/

		formData.append(
			"imagen",
			inputImagen.files[0]
		);


		/*
		Deshabilitar botón
		*/

		let boton =
			$(this);


		boton
			.prop("disabled", true)
			.text("Guardando...");


		/*
		AJAX
		*/

		$.ajax({

			url: '/guardar_firma',

			method: 'POST',

			data: formData,

			processData: false,

			contentType: false,


			success: function(response) {

				console.log(response);


				// try {

				// 	let data =
				// 		typeof response === "string" ?
				// 		JSON.parse(response) :
				// 		response;


				// 	if (data.ok) {

				// 		alert(
				// 			"Ticket cerrado correctamente."
				// 		);


						/*
						Abrir PDF en nueva pestaña
						*/

						// window.open(
						// 	"/pdf_ticket/" +
						// 	$("#ticket_id").val(),
						// 	"_blank"
						// );


						/*
						Recargar página
						*/

						location.reload();

			// 		} else {

			// 			alert(
			// 				data.mensaje ||
			// 				"No fue posible cerrar el ticket."
			// 			);

			// 			boton
			// 				.prop("disabled", false)
			// 				.text("Firmar y Cerrar Ticket");

			// 		}

			// 	} catch (error) {

			// 		console.error(response);

			// 		alert(
			// 			"Ocurrió un error al procesar la respuesta."
			// 		);

			// 		boton
			// 			.prop("disabled", false)
			// 			.text("Firmar y Cerrar Ticket");

			// 	}

			// },


			// error: function(xhr) {

			// 	console.error(
			// 		xhr.responseText
			// 	);


			// 	alert(
			// 		"Error al guardar el ticket."
			// 	);


			// 	boton
			// 		.prop("disabled", false)
			// 		.text("Firmar y Cerrar Ticket");

			}

		});

	});

	// $("#guardarFirma").click(function() {

	// 	if (firma.isEmpty()) {

	// 		alert(
	// 			"Debe capturar la firma."
	// 		);

	// 		return;
	// 	}


	// 	$.ajax({

	// 		url: '/guardar_firma',

	// 		method: 'POST',

	// 		data: {

	// 			ticket_id: $("#ticket_id").val(),

	// 			comentario: $("#comentario").val(),
	// 			solucion: $("#solucion").val(),
	// 			observaciones: $("#observaciones").val(),

	// 			firma: firma.toDataURL()

	// 		},


	// 		success: function(response) {

	// 			// window.location.href =
	// 			//     "/pdf_ticket/" +
	// 			//     $("#ticket_id").val();

	// 			// window.open(
	// 			//     "/pdf_ticket/" + $("#ticket_id").val(),
	// 			//     "_blank"
	// 			// );

	// 			// location.reload();

	// 			// // let ticket = $("#ticket_id").val();

	// 			// // Abrir PDF
	// 			// // window.open("/pdf_ticket/" + ticket, "_blank");

	// 			// Recargar ticket actual
	// 			location.reload();

	// 		}

	// 	});
	// });



	// $("#guardarFirma").click(function() {

	//     $.post(
	//         "/cerrar_ticket", {
	//             ticket_id: $("input[name=ticket_id]").val(),

	//             comentario: $("textarea[name=comentario]").val(),

	//             solucion: $("textarea[name=solucion]").val(),

	//             observaciones: $("textarea[name=observaciones]").val(),

	//             firma: firma.toDataURL()
	//         },
	//         function() {

	//             window.location.href =
	//                 "/pdf_ticket/" +
	//                 $("input[name=ticket_id]").val();

	//         }
	//     );

	// });

	const viewer = new Viewer(
		document.getElementById('imagenGrande'), {
			navbar: false,
			toolbar: true
		}
	);

	// function verImagen(src) {

	//     $("#imagenGrande").attr(
	//         "src",
	//         src
	//     );

	//     const modal =
	//         new bootstrap.Modal(
	//             document.getElementById(
	//                 "modalImagen"
	//             )
	//         );

	//     modal.show();
	// }
	$('.primera-mayuscula').on('input', function() {
		let texto = $(this).val();
		if (texto.length > 0) {
			// Convierte solo el primer carácter a mayúscula
			$(this).val(texto.charAt(0).toUpperCase() + texto.slice(1));
		}
	});

	$("#btnSolicitarFirma").click(function() {

		let formData = new FormData(document.getElementById("segForm"));

		$.ajax({

			url: "../api/solicitar_firma.php",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,


			success: function(response) {
				console.log(response);

				let enlace = window.location.origin + "/firma_ticket?token=" + response;

				$("#linkFirma").val(enlace);

				$("#modalLink").modal("show");

			}
		});
	});

	function copiarLink() {
		// Selecciona los elementos usando selectores de jQuery
		const inputFirma = $("#linkFirma");
		const boton = $("#btnCopiar");
		const textoBoton = $("#textoBoton");

		// Valida si el input está vacío usando .val()
		if (!inputFirma.val()) return;

		// Copia el enlace al portapapeles
		navigator.clipboard.writeText(inputFirma.val())
			.then(() => {
				// 1. Cambia el diseño a éxito con métodos de jQuery
				textoBoton.text("¡Copiado!");
				boton.removeClass("btn-primary")
					.addClass("btn-success")
					.prop("disabled", true); // Deshabilita el botón

				// 2. Regresa al estado original después de 2 segundos (2000 ms)
				setTimeout(() => {
					textoBoton.text("Copiar");
					boton.removeClass("btn-success")
						.addClass("btn-primary")
						.prop("disabled", false); // Habilita el botón
				}, 2000);
			})
			.catch(err => {
				console.error("Error al copiar: ", err);
			});
	}

	let ventanaWhatsApp = null;

	function enviarWhatsApp() {

		// Teléfono registrado en el ticket
		let telefono = "<?php echo htmlspecialchars($ticket['telefono'] ?? '', ENT_QUOTES, 'UTF-8'); ?>";

		if (!telefono) {

			Swal.fire({
				icon: 'warning',
				title: 'Sin teléfono',
				text: 'Este ticket no tiene un número de teléfono registrado.'
			});

			return;
		}

		// Dejamos únicamente números
		telefono = telefono.replace(/\D/g, '');

		// Si el número es de México y tiene 10 dígitos
		if (telefono.length === 10) {
			telefono = '52' + telefono;
		}

		// Obtener comentario escrito por el técnico
		let comentario = document.getElementById('comentario').value.trim();

		let mensaje =
			"Hola <?php echo htmlspecialchars($ticket['reportante'] ?? '', ENT_QUOTES, 'UTF-8'); ?>,\n" +
			"Le contactamos respecto a su reporte con folio *<?php echo htmlspecialchars($ticket['folio'] ?? '', ENT_QUOTES, 'UTF-8'); ?>* - " +
			"*<?php echo htmlspecialchars($ticket['titulo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>*.\n";

		if (comentario !== '') {
			mensaje += comentario + "\n";
		}

		mensaje +=
			"Estamos dando seguimiento a su solicitud.\n" +
			"Saludos.";

		// Codificar mensaje para URL
		let url = "https://wa.me/" + telefono + "?text=" + encodeURIComponent(mensaje);

		if (ventanaWhatsApp && !ventanaWhatsApp.closed) {

			ventanaWhatsApp.location.href = url;
			ventanaWhatsApp.focus();

		} else {

			ventanaWhatsApp = window.open(url, 'whatsapp_ticket');
		}

		// // Abrir WhatsApp
		// window.open(url, '_blank');
	}



	//     $.post(
	//         "api/solicitar_firma.php", {
	//             ticket_id: $("#ticket_id").val()
	//         },
	//         function(response) {

	//             response =
	//                 JSON.parse(response);

	//             console.log(response);

	//             // let enlace =
	//             //     window.location.origin +
	//             //     "/firma_ticket?token=" +
	//             //     response.token;

	//             // $("#linkFirma")
	//             //     .val(enlace);

	//             // $("#modalLink")
	//             //     .modal("show");

	//         }
	//     );

	// });
</script>