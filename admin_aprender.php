<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Seguridad: Solo admin
if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

$materiales = mysqli_query($conexion, "SELECT * FROM guia_materiales");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Guía de Aprendizaje</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 40px; max-width: 1000px; margin: auto; }
        .tabla-admin { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        .tabla-admin th, .tabla-admin td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .tabla-admin th { background: #2e7d32; color: white; }
        .img-preview { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; }
        .btn-edit { background: #2196F3; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Gestionar Guía de Materiales</h1>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="admin_dashboard.php">← Volver al Panel</a>
            <a href="nuevo_material.php" class="btn-primario" style="text-decoration: none; padding: 10px 20px;">+ Nuevo Material</a>
        </div>

        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Material</th>
                    <th>Valor Puntos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($m = mysqli_fetch_assoc($materiales)): ?>
                <tr>
                    <td><img src="<?php echo $m['imagen']; ?>" class="img-preview"></td>
                    <td><?php echo $m['titulo']; ?></td>
                    <td><strong><?php echo $m['valor_puntos']; ?></strong></td>
                    <td>
                        <a href="editar_material.php?id=<?php echo $m['id']; ?>" class="btn-edit">Editar Guía</a>
    
                        <a href="admin_aprender.php?eliminar=<?php echo $m['id']; ?>" 
                            class="btn-del" 
                            style="background: #f44336; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-left: 5px;"
                            onclick="return confirm('¿Estás seguro de que quieres borrar este material? Esta acción no se puede deshacer.')">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>