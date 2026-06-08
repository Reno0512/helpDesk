<!-- Botón Hamburguesa: Solo visible en pantallas pequeñas (d-md-none) -->
<div class="d-md-none p-3 bg-dark text-white d-flex align-items-center justify-content-between">
    <h4>Área de Informática</h4>
    <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="bi bi-list fs-3"></i> ☰
    </button>
</div>

<div class="d-flex">

    <!-- Sidebar: Fijo en pantallas grandes, Offcanvas en pantallas chicas -->
    <div class="offcanvas-md offcanvas-start bg-dark text-white p-3 flex-shrink-0"
        tabindex="-1"
        id="sidebarMenu"
        style="width: 250px; min-height: 94vh;">

        <!-- Encabezado del menú (útil para el botón de cerrar en móviles) -->
        <div class="d-flex justify-content-between align-items-center d-md-none mb-3">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>

        <h4>Área de Informática</h4>
        <h6><?= $_SESSION["nombre"] ?></h6>
        <hr>

        <ul class="nav flex-column">
            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="/dashboard" class="text-white text-decoration-none">Dashboard</a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="/tickets" class="text-white text-decoration-none">Tickets</a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="?url=activos" class="text-white text-decoration-none">Activos</a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="/reportes" class="text-white text-decoration-none">Reportes</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Contenido Principal -->
    <div class="p-4 w-100">