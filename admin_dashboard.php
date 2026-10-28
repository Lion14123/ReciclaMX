<?php 
include("admin_auth.php"); 
include("conexion.php"); // Asegúrate de incluir la conexión

// LÓGICA PARA BORRAR NOTIFICACIÓN
if (isset($_GET['borrar_notif'])) {
    $id_notif = mysqli_real_escape_string($conexion, $_GET['borrar_notif']);
    mysqli_query($conexion, "DELETE FROM notificaciones WHERE id = '$id_notif'");
    header("Location: admin_dashboard.php");
    exit();
}

// CONSULTAR NOTIFICACIONES NO LEÍDAS
$notificaciones = mysqli_query($conexion, "SELECT * FROM notificaciones ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - ReciclaMx Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .admin-card { background: #f4f4f4; padding: 20px; border-radius: 10px; text-align: center; border-top: 5px solid #2e7d32; }
        .admin-card h3 { margin-bottom: 15px; }
        .btn-admin { display: inline-block; padding: 10px 20px; background: #2e7d32; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <header>
        <h1>Consola de Administración</h1>
        <div class="perfil-acciones">
                    <a href="logout.php" class="btn-logout" style="color: red;">Cerrar Sesión</a>
        </div>

    </header>

    <div class="admin-grid">
        <div class="admin-card">
            <h3>Puntos de Recolección</h3>
            <p>Gestionar centros de acopio.</p>
            <a href="admin_puntos.php" class="btn-admin">Administrar</a>
        </div>
        <div class="admin-card">
            <h3>Guía de Aprendizaje</h3>
            <p>Actualizar materiales y consejos.</p>
            <a href="admin_aprender.php" class="btn-admin">Administrar</a>
        </div>
        <div class="admin-card">
            <h3>Agendas a Domicilio</h3>
            <p>Ver solicitudes y marcar como completadas.</p>
            <a href="admin_agendas.php" class="btn-admin">Ver Solicitudes</a>
        </div>
        <div class="admin-card">
            <h3>Catálogo de Recompensas</h3>
            <p>Actualizar stock, precios e imágenes.</p>
            <a href="admin_recompensas.php" class="btn-admin">Gestionar Stock</a>
        </div>
        <div class="admin-card">
            <h3>Gestión de Usuarios</h3>
            <p>Sumar/Restar puntos y crear nuevos admins.</p>
            <a href="admin_usuarios.php" class="btn-admin">Control de Usuarios</a>
        </div>
    </div>

    <div class="seccion-notificaciones" style="padding: 0 20px; margin-top: 20px;">
    <h2 style="color: #d32f2f; font-size: 1.2rem;">⚠️ Alertas del Sistema</h2>
    
    <?php if (mysqli_num_rows($notificaciones) > 0): ?>
        <div class="contenedor-alertas" style="background: #fff5f5; border-left: 5px solid #d32f2f; padding: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <?php while($n = mysqli_fetch_assoc($notificaciones)): ?>
                <div class="alerta-item" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #ffcdd2;">
                    <div>
                        <span style="font-weight: bold; color: #b71c1c;">[<?php echo date('H:i', strtotime($n['fecha'])); ?>]</span>
                        <span style="margin-left: 10px;"><?php echo htmlspecialchars($n['mensaje']); ?></span>
                    </div>
                    <a href="admin_dashboard.php?borrar_notif=<?php echo $n['id']; ?>" 
                       style="background: #d32f2f; color: white; padding: 5px 12px; text-decoration: none; border-radius: 4px; font-size: 0.8rem;"
                       onclick="return confirm('¿Marcar como atendida?')">
                       Eliminar
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p style="color: #2e7d32; background: #e8f5e9; padding: 15px; border-radius: 5px;">✅ No hay alertas pendientes. Todo está bajo control.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(isset($_SESSION['nombre'])): ?>
<script>
    // Solo mostramos el saludo una vez por sesión
    if (!sessionStorage.getItem('bienvenidaMostrada')) {
        Swal.fire({
            title: '¡Hola, <?php echo $_SESSION['nombre']; ?>!',
            text: 'Has iniciado sesión correctamente.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        sessionStorage.setItem('bienvenidaMostrada', 'true');
    }
</script>
<?php endif; ?>
</body>
</html>