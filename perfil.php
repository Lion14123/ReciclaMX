<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// Si no hay sesión iniciada, mandarlo al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Consultar datos actualizados del usuario
$id_user = $_SESSION['usuario_id'];
$query_user = "SELECT nombre, puntos, tag FROM usuarios WHERE id = '$id_user'";
$res_user = mysqli_query($conexion, $query_user);
$user = mysqli_fetch_assoc($res_user);

// CONSULTA DEL HISTORIAL: Filtramos por el usuario logueado
// Usamos JOIN para traer el nombre del producto desde la otra tabla
$query_historial = "SELECT h.fecha_canje, h.puntos_canjeados, p.nombre AS producto_nombre 
                    FROM historial_canjes h
                    JOIN productos p ON h.producto_id = p.id
                    WHERE h.usuario_id = '$id_user' 
                    ORDER BY h.fecha_canje DESC";
$res_historial = mysqli_query($conexion, $query_historial);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - ReciclaMx</title>
    <link rel="stylesheet" href="style.css">
    <style>
    /* Contenedor principal del historial */
    .historial-seccion { 
        margin: 30px auto 0 auto; /* Centra el bloque horizontalmente */
        width: 100%; 
        max-width: 800px; /* Un poco más ancho para mejor lectura */
    }

    .historial-seccion h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #2e7d32;
    }

    /* Estilos de la Tabla */
    .tabla-historial { 
        width: 100%; 
        margin: 0 auto; /* Asegura el centrado de la tabla */
        border-collapse: collapse; 
        background: white; 
        color: #333; 
        border-radius: 8px; 
        overflow: hidden; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .tabla-historial th, .tabla-historial td { 
        padding: 15px; 
        text-align: center; /* Centra el texto dentro de las celdas */
        border-bottom: 1px solid #eee; 
    }

    .tabla-historial th { 
        background-color: #2e7d32; 
        color: white; 
        font-weight: bold;
    }

    .puntos-negativos { 
        color: #d32f2f; 
        font-weight: bold; 
    }

    /* Mensaje cuando no hay datos */
    .no-datos {
        text-align: center;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        color: #666;
    }
</style>
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <section id="perfil">
            <h1>Mi Perfil de Reciclador</h1>

            <div class="perfil-container">
                <img src="img/user.png" alt="Foto de Perfil" class="perfil-foto">
                
                <h2><?php echo htmlspecialchars($user['nombre']); ?></h2>
                <p>Tipo de cuenta: <strong><?php echo ucfirst(htmlspecialchars($user['tag'])); ?></strong></p>
                
                <div class="user-points-panel">
                    <p>Puntos Disponibles</p>
                    <h3 style="color:white"><?php echo number_format($user['puntos']); ?></h3>
                </div>

                <div class="historial-seccion">
    <h3>Mis Canjes Recientes</h3>
    <?php if(mysqli_num_rows($res_historial) > 0): ?>
        <table class="tabla-historial">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Puntos</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_assoc($res_historial)): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($fila['fecha_canje'])); ?></td>
                        <td><?php echo htmlspecialchars($fila['producto_nombre']); ?></td>
                        <td class="puntos-negativos">-<?php echo number_format($fila['puntos_canjeados']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-datos">
            <p>Aún no has realizado ningún canje. ¡Sigue reciclando!</p>
        </div>
    <?php endif; ?>
</div>

                <br>
                <div class="perfil-acciones">
                    <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 ReciclaMx. Todos los derechos reservados.</p>
    </footer>

</body>
</html>