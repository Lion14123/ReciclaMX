<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

// Lógica para ELIMINAR producto
if (isset($_GET['eliminar'])) {
    $id_del = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM productos WHERE id = '$id_del'");
    header("Location: admin_recompensas.php?msj=eliminado");
}

$productos = mysqli_query($conexion, "SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Recompensas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 40px; max-width: 1000px; margin: auto; }
        .tabla-admin { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        .tabla-admin th, .tabla-admin td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        .tabla-admin th { background: #2e7d32; color: white; }
        .stock-bajo { color: #d32f2f; font-weight: bold; background: #ffebee; }
        .img-prod { width: 60px; height: 60px; object-fit: contain; }
        .btn-nuevo { background: #2e7d32; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; float: right; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Gestión de Inventario de Recompensas</h1>
        <a href="admin_dashboard.php">← Volver al Panel</a>
        <a href="nuevo_producto.php" class="btn-nuevo">+ Nuevo Producto</a>

        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio (Pts)</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($p = mysqli_fetch_assoc($productos)): ?>
                <tr>
                    <td><img src="<?php echo $p['imagen_url']; ?>" class="img-prod"></td>
                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                    <td><?php echo number_format($p['costo_puntos']); ?></td>
                    <td class="<?php echo ($p['stock'] <= 5) ? 'stock-bajo' : ''; ?>">
                        <?php echo $p['stock']; ?>
                        <?php if($p['stock'] <= 5) echo "<br><small>⚠️ RELLENAR</small>"; ?>
                    </td>
                    <td>
                        <a href="editar_producto.php?id=<?php echo $p['id']; ?>" style="color: blue;">Editar</a> | 
                        <a href="admin_recompensas.php?eliminar=<?php echo $p['id']; ?>" 
                           style="color: red;" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>