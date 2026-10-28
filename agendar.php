<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// Seguridad: Solo usuarios logueados pueden agendar
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo_desecho']);
    $desc = mysqli_real_escape_string($conexion, $_POST['descripcion']);

    $sql = "INSERT INTO solicitudes_recoleccion (usuario_id, direccion, telefono, fecha_programada, tipo_residuo, descripcion) 
            VALUES ('$usuario_id', '$direccion', '$telefono', '$fecha', '$tipo', '$desc')";

    if (mysqli_query($conexion, $sql)) {
    $mensaje = "exito"; // Cambiamos el texto por una palabra clave
} else {
    $mensaje = "error";
}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReciclaMx - Agendar Recolección</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <section id="agendar">
            <h1>Agendar Recolección a Domicilio</h1>
            <p style="text-align:center; margin-bottom:2rem;">Mínimo para recolección: 7kg de material reciclable mezclado.</p>

            <div class="form-container">
                <form action="agendar.php" method="POST">
                    
                    <div class="form-group">
                        <label for="direccion">Dirección de Recolección:</label>
                        <input type="text" id="direccion" name="direccion" required placeholder="Calle, Número, Colonia, CP">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono de Contacto:</label>
                        <input type="tel" id="telefono" name="telefono" required placeholder="55 1234 5678">
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha Preferente:</label>
                        <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo_desecho">Tipo de Residuo Principal:</label>
                        <select id="tipo_desecho" name="tipo_desecho">
                            <option value="Plásticos">Plásticos (PET/HDPE)</option>
                            <option value="Cartón">Cartón y Papel</option>
                            <option value="Metales">Metales (Cobre, Aluminio)</option>
                            <option value="Electrónicos">Basura Tecnológica</option>
                            <option value="Mixto">Mixto (Varios tipos)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción Detallada (Opcional):</label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Ej: 3 bolsas grandes de PET aplastado."></textarea>
                    </div>

                    <button type="submit" class="btn-primario" style="width:100%;">Solicitar Recolección</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 ReciclaMx. Todos los derechos reservados.</p>
    </footer>
<?php if ($mensaje === "exito"): ?>
<script>
    Swal.fire({
        title: '¡Cita Agendada!',
        text: 'Pronto nos comunicaremos contigo para confirmar la recolección.',
        icon: 'success',
        confirmButtonColor: '#2e7d32',
        confirmButtonText: 'Entendido'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '#'; // Opcional: Redirigir al inicio tras aceptar
        }
    });
</script>
<?php elseif ($mensaje === "error"): ?>
<script>
    Swal.fire({
        title: 'Hubo un problema',
        text: 'No pudimos agendar tu cita. Por favor, intenta de nuevo.',
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Cerrar'
    });
</script>
<?php endif; ?>
</body>
</html>