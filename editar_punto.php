<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Seguridad: solo admin puede editar
if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 1. Obtener los datos actuales del punto
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = mysqli_query($conexion, "SELECT * FROM centros_acopio WHERE id = '$id'");
    $punto = mysqli_fetch_assoc($res);
}

// 2. Procesar la actualización (UPDATE)
if (isset($_POST['actualizar'])) {
    $id_upd = $_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $dir = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $horario = mysqli_real_escape_string($conexion, $_POST['horario']);
    $mats = mysqli_real_escape_string($conexion, $_POST['materiales']);

    $sql = "UPDATE centros_acopio 
            SET nombre='$nombre', direccion='$dir', horario='$horario', materiales='$mats' 
            WHERE id='$id_upd'";
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_puntos.php?msj=editado");
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Centro - ReciclaMx Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .edit-container { padding: 40px; max-width: 600px; margin: auto; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Editar Centro de Acopio</h1>
        <form action="editar_punto.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $punto['id']; ?>">
            
            <div class="form-group">
                <label>Nombre del Centro:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($punto['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($punto['direccion']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Horario:</label>
                <input type="text" name="horario" value="<?php echo htmlspecialchars($punto['horario']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Materiales:</label>
                <input type="text" name="materiales" value="<?php echo htmlspecialchars($punto['materiales']); ?>" required>
            </div>

            <button type="submit" name="actualizar" class="btn-primario" style="width:100%;">Guardar Cambios</button>
            <br><br>
            <a href="admin_puntos.php" style="display:block; text-align:center;">Cancelar y Volver</a>
        </form>
    </div>
</body>
</html>