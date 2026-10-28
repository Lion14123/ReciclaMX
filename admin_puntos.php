<?php
include("conexion.php");
// Filtro de seguridad para asegurar que solo entre el admin
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

// LÓGICA PARA INSERTAR NUEVO PUNTO
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $dir = $_POST['direccion'];
    $horario = $_POST['horario'];
    $mats = $_POST['materiales'];
    
    $query = "INSERT INTO centros_acopio (nombre, direccion, horario, materiales) VALUES ('$nombre', '$dir', '$horario', '$mats')";
    mysqli_query($conexion, $query);
    header("Location: admin_puntos.php?msj=agregado");
}

// LÓGICA PARA ELIMINAR
if (isset($_GET['eliminar'])) {
    $id_del = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM centros_acopio WHERE id = '$id_del'");
    header("Location: admin_puntos.php?msj=eliminado");
}

$puntos = mysqli_query($conexion, "SELECT * FROM centros_acopio");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Centros de Acopio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 40px; max-width: 1000px; margin: auto; }
        .form-admin { background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .tabla-admin { width: 100%; border-collapse: collapse; background: white; }
        .tabla-admin th, .tabla-admin td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .tabla-admin th { background: #2e7d32; color: white; }
        .btn-edit { color: blue; text-decoration: none; margin-right: 10px; }
        .btn-del { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Gestionar Centros de Acopio</h1>
        <a href="admin_dashboard.php">← Volver al Panel</a>

        <div class="form-admin">
            <h3>Agregar Nuevo Centro</h3>
            <form action="admin_puntos.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre del centro" required style="width: 23%; padding: 8px;">
                <input type="text" name="direccion" placeholder="Dirección completa" required style="width: 23%; padding: 8px;">
                <input type="text" name="horario" placeholder="Ej: Lun-Vie 8:00-18:00" required style="width: 23%; padding: 8px;">
                <input type="text" name="materiales" placeholder="PET, Cartón, etc." required style="width: 23%; padding: 8px;">
                <button type="submit" name="agregar" class="btn-primario" style="margin-top:10px;">Guardar Punto</button>
            </form>
        </div>

        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Horario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($puntos)): ?>
                <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['direccion']; ?></td>
                    <td><?php echo $row['horario']; ?></td>
                    <td>
                        <a href="editar_punto.php?id=<?php echo $row['id']; ?>" class="btn-edit">Editar</a>
                        <a href="admin_puntos.php?eliminar=<?php echo $row['id']; ?>" class="btn-del" onclick="return confirm('¿Eliminar este centro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>