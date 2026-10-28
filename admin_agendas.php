<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Protección de ruta para Administrador
if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

// Lógica para actualizar el ESTADO
if (isset($_POST['actualizar_estado'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $nuevo_estado = $_POST['estado'];
    
    $query = "UPDATE solicitudes_recoleccion SET estado = '$nuevo_estado' WHERE id = '$id_solicitud'";
    mysqli_query($conexion, $query);
    header("Location: admin_agendas.php?msj=actualizado");
}

// LÓGICA PARA ELIMINAR AGENDA
if (isset($_GET['eliminar'])) {
    $id_del = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    
    $query_del = "DELETE FROM solicitudes_recoleccion WHERE id = '$id_del'";
    if (mysqli_query($conexion, $query_del)) {
        header("Location: admin_agendas.php?msj=eliminado");
        exit();
    }
}

// Consulta uniendo tablas para obtener el nombre del usuario
$query_agendas = "SELECT s.*, u.nombre as cliente 
                  FROM solicitudes_recoleccion s 
                  JOIN usuarios u ON s.usuario_id = u.id 
                  ORDER BY s.fecha_programada DESC";
$resultado = mysqli_query($conexion, $query_agendas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Agendas - ReciclaMx</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 40px; max-width: 1100px; margin: auto; }
        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 0.85rem; font-weight: bold; }
        .pendiente { background: #fff3e0; color: #ef6c00; }
        .en-camino { background: #e3f2fd; color: #1565c0; }
        .completado { background: #e8f5e9; color: #2e7d32; }
        .cancelado { background: #ffebee; color: #c62828; }
        .tabla-agendas { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        .tabla-agendas th, .tabla-agendas td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .tabla-agendas th { background: #2e7d32; color: white; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Solicitudes de Recolección</h1>
        <a href="admin_dashboard.php">← Volver al Panel</a>

        <table class="tabla-agendas">
            <thead>
                <tr>
                    <th>Fecha Cita</th>
                    <th>Cliente</th>
                    <th>Residuo</th>
                    <th>Dirección / Tel</th>
                    <th>Estado Actual</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($row['fecha_programada'])); ?></td>
                    <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($row['tipo_residuo']); ?></td>
                    <td>
                        <small>
                            <?php echo htmlspecialchars($row['direccion']); ?><br>
                            <strong>Tel:</strong> <?php echo htmlspecialchars($row['telefono']); ?>
                        </small>
                    </td>
                    <td>
                        <span class="status-badge <?php echo str_replace(' ', '-', $row['estado']); ?>">
                            <?php echo strtoupper($row['estado']); ?>
                        </span>
                    </td>
                    <td>
    <div style="display: flex; align-items: center; gap: 10px;">
        <form method="POST" style="display:flex; gap:5px;">
            <input type="hidden" name="id_solicitud" value="<?php echo $row['id']; ?>">
            <select name="estado" style="padding:5px; border-radius:4px;">
                <option value="pendiente" <?php if($row['estado']=='pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="en camino" <?php if($row['estado']=='en camino') echo 'selected'; ?>>En camino</option>
                <option value="completado" <?php if($row['estado']=='completado') echo 'selected'; ?>>Completado</option>
                <option value="cancelado" <?php if($row['estado']=='cancelado') echo 'selected'; ?>>Cancelado</option>
            </select>
            <button type="submit" name="actualizar_estado" class="btn-primario" style="padding:5px 10px;">OK</button>
        </form>

        <a href="admin_agendas.php?eliminar=<?php echo $row['id']; ?>" 
           onclick="return confirm('¿Borrar esta solicitud permanentemente?')"
           style="background: #ff5252; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
           Eliminar
        </a>
    </div>
</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>