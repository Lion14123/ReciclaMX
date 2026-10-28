<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

$id = mysqli_real_escape_string($conexion, $_GET['id']);
$res = mysqli_query($conexion, "SELECT * FROM productos WHERE id = '$id'");
$p = mysqli_fetch_assoc($res);

if (isset($_POST['actualizar'])) {
    $id_upd = $_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $puntos = intval($_POST['puntos_valor']); // Coincide con el name del input
    $stock = intval($_POST['stock']);

    if (!empty($_FILES['foto']['name'])) {
        $ruta = "img/" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        // CORRECCIÓN: Nombres de columnas según tu SQL (costo_puntos e imagen_url)
        $sql = "UPDATE productos SET nombre='$nombre', costo_puntos='$puntos', stock='$stock', imagen_url='$ruta' WHERE id='$id_upd'";
    } else {
        // CORRECCIÓN: Nombre de columna según tu SQL (costo_puntos)
        $sql = "UPDATE productos SET nombre='$nombre', costo_puntos='$puntos', stock='$stock' WHERE id='$id_upd'";
    }

    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_recompensas.php?msj=ok");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Recompensa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 500px; margin: 50px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2>Editar Producto</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
            
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($p['nombre']); ?>" required style="width:100%; padding:10px; margin-bottom:15px;">

            <label>Puntos Necesarios:</label>
            <input type="number" name="puntos_valor" value="<?php echo $p['costo_puntos']; ?>" required style="width:100%; padding:10px; margin-bottom:15px;">

            <label style="color: #d32f2f; font-weight: bold;">Stock Disponible:</label>
            <input type="number" name="stock" value="<?php echo $p['stock']; ?>" required style="width:100%; padding:10px; margin-bottom:15px; border: 2px solid #2e7d32;">

            <label>Imagen Actual:</label><br>
            <img src="<?php echo $p['imagen_url']; ?>" width="80" style="margin-bottom:10px;"><br>
            
            <label>Cambiar Imagen (Opcional):</label>
            <input type="file" name="foto" accept="image/*" style="margin-top:10px; margin-bottom:20px;">

            <button type="submit" name="actualizar" class="btn-primario" style="width:100%; padding:12px; cursor:pointer;">Guardar Cambios</button>
            <a href="admin_recompensas.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancelar</a>
        </form>
    </div>
</body>
</html>