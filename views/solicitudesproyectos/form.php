<?php require __DIR__ . '/../../includes/header2.php'; ?>

<div class="card bg-dark text-white my-4 mx-auto" style="max-width: 650px;">
    <div class="card-body">
        <h1 class="h3"><?= $tipo === 'reserva' ? 'Reservar' : 'Pedir' ?> un proyecto</h1>
        <p><?= htmlspecialchars($proyecto->getNombre()) ?></p>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['solicitud_enviada'])): ?>
            <div class="alert alert-success" role="status"><?= htmlspecialchars($_SESSION['solicitud_enviada'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php unset($_SESSION['solicitud_enviada']); ?>
        <?php endif; ?>
        <p class="small">La biblioteca revisará tu solicitud antes de confirmar el préstamo o la reserva.</p>

        <form method="POST" action="index.php?accion=guardarSolicitudProyecto">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['solicitud_csrf'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="proyecto_id" value="<?= (int) $proyecto->getId() ?>">
            <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">
            <label for="modalidad" class="form-label">¿Ya sos socio de la biblioteca?</label>

            <select name="modalidad" id="modalidad" class="form-select mb-3">
                <option value="existente">
                    Sí, solicitar con mi cédula
                </option>

                <option value="nuevo" <?= ($_POST['modalidad'] ?? '') === 'nuevo' ? 'selected' : '' ?>>
                    No, quiero registrarme como socio
                </option>
            </select>

            <label for="cedula" class="form-label">Cédula</label>
            <input id="cedula" name="cedula" class="form-control mb-3" maxlength="20" 
                   value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>" required>
            
            <div id="datos-nuevo">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input id="nombre" name="nombre" class="form-control mb-3" maxlength="150" 
                       value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">

                <label for="clase" class="form-label">Clase o grupo</label>
                <input id="clase" name="clase" class="form-control mb-3" maxlength="100" 
                       value="<?= htmlspecialchars($_POST['clase'] ?? '') ?>">
            </div>
            
            <label for="telefono" class="form-label">Teléfono de contacto <span id="telefono-opcional">(opcional)</span></label>
            <input id="telefono" name="telefono" type="tel" class="form-control mb-3" maxlength="40" 
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">

            <button class="btn btn-success" type="submit">
                Enviar solicitud
            </button>        

            <a class="btn btn-secondary" href="index.php?accion=listarProyectosP">Volver al catálogo</a>
        
        </form>
    </div>
</div>


<script>
(() => {
    const modalidad = document.getElementById('modalidad');
    function actualizar() {
        const nuevo = modalidad.value === 'nuevo';
        document.getElementById('datos-nuevo').hidden = !nuevo;
        document.getElementById('telefono-opcional').hidden = nuevo;
        ['nombre', 'clase', 'telefono'].forEach(id => {
            document.getElementById(id).required = nuevo;
        });
    }
    modalidad.addEventListener('change', actualizar);
    actualizar();
})();
</script>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
