<div class="d-flex">

    <div
        class="bg-dark text-white p-3"
        style="
        width:250px;
        height:94vh;
        ">

        <h4>Área de Informática</h4>

        <hr>

        <ul class="nav flex-column">

            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="/dashboard"
                        class="text-white text-decoration-none">
                        Dashboard
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="/tickets"
                        class="text-white text-decoration-none">
                        Tickets
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="?url=activos"
                        class="text-white text-decoration-none">
                        Activos
                    </a>
                </li>
            <?php endif; ?>


            <?php if ($_SESSION["rol"] === "admin"): ?>
                <li class="nav-item mb-2">
                    <a href="/reportes"
                        class="text-white text-decoration-none">
                        Reportes
                    </a>
                </li>
            <?php endif; ?>


        </ul>

    </div>

    <div class="p-4 w-100">