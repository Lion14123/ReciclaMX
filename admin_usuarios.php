<?php
include("conexion.php");
include("admin_auth.php");

// 1. LÓGICA DE ACCIONES (ELIMINAR, BLOQUEAR, HACER ADMIN)
if (isset($_GET['eliminar'])) {
    $id_del = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM usuarios WHERE id = '$id_del' AND tag != 'admin'");
    header("Location: admin_usuarios.php?msj=eliminado");
    exit();
}

if (isset($_GET['toggle_estado'])) {
    $id_st = mysqli_real_escape_string($conexion, $_GET['toggle_estado']);
    $nuevo_estado = ($_GET['st'] == 'activo') ? 'inactivo' : 'activo';
    mysqli_query($conexion, "UPDATE usuarios SET estado = '$nuevo_estado' WHERE id = '$id_st'");
    header("Location: admin_usuarios.php");
    exit();
}

// CAMBIAR RANGO (Hacer Admin o Quitar Privilegios)
if (isset($_GET['cambiar_rango'])) {
    $id_user = mysqli_real_escape_string($conexion, $_GET['cambiar_rango']);
    $nuevo_tag = ($_GET['rol'] == 'admin') ? 'usuario' : 'admin';
    
    // Seguridad: No puedes quitarte el admin a ti mismo para no quedarte fuera del sistema
    if($id_user != $_SESSION['usuario_id']) {
        mysqli_query($conexion, "UPDATE usuarios SET tag = '$nuevo_tag' WHERE id = '$id_user'");
    }
    header("Location: admin_usuarios.php");
    exit();
}

// 2. LÓGICA DEL BUSCADOR
$busqueda = "";
if (isset($_POST['buscar'])) {
    $busqueda = mysqli_real_escape_string($conexion, $_POST['termino']);
    // Busca coincidencias en nombre o correo
    $query = "SELECT * FROM usuarios WHERE nombre LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' ORDER BY tag ASC, nombre ASC";
} else {
    // Consulta por defecto (todos los usuarios)
    $query = "SELECT * FROM usuarios ORDER BY tag ASC, nombre ASC";
}
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Cuentas - ReciclaMx</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status-activo { color: #2e7d32; font-weight: bold; }
        .status-inactivo { color: #d32f2f; font-weight: bold; }
        .btn-accion { padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 0.85rem; color: white; border: none; cursor: pointer; }
        .search-container { margin: 20px 0; display: flex; gap: 10px; }
        .search-input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-container" style="padding: 20px; max-width: 1150px; margin: auto;">
        <h1>Gestión Avanzada de Usuarios</h1>
        <a href="admin_dashboard.php" style="text-decoration: none; color: #2e7d32;">← Volver al Panel</a>

        <form method="POST" class="search-container">
            <input type="text" name="termino" class="search-input" placeholder="Buscar por nombre o correo electrónico..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" name="buscar" class="btn-accion" style="background: #2e7d32; padding: 10px 20px;">Buscar Usuario</button>
            <?php if($busqueda != ""): ?>
                <a href="admin_usuarios.php" style="padding: 10px; color: #666;">Limpiar filtro</a>
            <?php endif; ?>
        </form>

        <table class="tabla-admin" style="width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #2e7d32; color: white;">
                    <th style="padding: 15px;">Usuario</th>
                    <th>Correo</th>
                    <th>Rango</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <th>Acciones de Control</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($resultado) > 0): ?>
                    <?php while($u = mysqli_fetch_assoc($resultado)): ?>
                    <tr style="border-bottom: 1px solid #eee; text-align: center;">
                        <td style="padding: 15px;"><?php echo htmlspecialchars($u['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($u['correo']); ?></td>
                        <td><span style="background: #f0f0f0; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;"><?php echo strtoupper($u['tag']); ?></span></td>
                        <td><strong><?php echo number_format($u['puntos']); ?></strong></td>
                        <td class="status-<?php echo $u['estado']; ?>"><?php echo strtoupper($u['estado']); ?></td>
                        <td style="padding: 10px; display: flex; gap: 8px; justify-content: center;">
                            
                            <a href="asignar_puntos.php?id=<?php echo $u['id']; ?>" class="btn-accion" style="background: #fb8c00;" title="Asignar Puntos">+ Puntos</a>
                            
                          <?php if($u['id'] != $_SESSION['usuario_id']): ?>
                            <a href="admin_usuarios.php?cambiar_rango=<?php echo $u['id']; ?>&rol=<?php echo $u['tag']; ?>" 
                               class="btn-accion" style="background: #673ab7; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.8rem;"
                               onclick="return confirm('¿Cambiar el nivel de privilegio de este usuario?')">
                               <?php echo ($u['tag'] == 'admin') ? 'Quitar Admin' : 'Hacer Admin'; ?>
                            </a>
                        <?php endif; ?>

                            <a href="admin_usuarios.php?toggle_estado=<?php echo $u['id']; ?>&st=<?php echo $u['estado']; ?>" 
                               class="btn-accion" style="background: #607d8b;">
                               <?php echo ($u['estado'] == 'activo') ? 'Inhabilitar' : 'Habilitar'; ?>
                            </a>

                            <?php if($u['tag'] != 'admin'): ?>
                                <a href="admin_usuarios.php?eliminar=<?php echo $u['id']; ?>" 
                                   class="btn-accion" style="background: #d32f2f;" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta cuenta?')">Eliminar</a>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; color: #999;">No se encontraron usuarios que coincidan con "<?php echo htmlspecialchars($busqueda); ?>"</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>