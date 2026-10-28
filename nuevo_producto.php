<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Filtro de seguridad: Solo admin
if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

if (isset($_POST['guardar'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $puntos = intval($_POST['costo_puntos']);
    $stock = intval($_POST['stock']);

    // Procesamiento de la Imagen
    $nombre_img = $_FILES['imagen']['name'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $ext = pathinfo($nombre_img, PATHINFO_EXTENSION);
    
    $nuevo_nombre_img = "prod_" . time() . "." . $ext;
    $ruta_destino = "img/" . $nuevo_nombre_img;

    if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // CORRECCIÓN: Se cambiaron los nombres de las columnas a 'costo_puntos' e 'imagen_url'
        $sql = "INSERT INTO productos (nombre, costo_puntos, stock, imagen_url) 
                VALUES ('$nombre', $puntos, $stock, '$ruta_destino')";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: admin_recompensas.php?msj=creado");
            exit();
        } else {
            echo "Error en la base de datos: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al subir la imagen. Revisa los permisos de la carpeta img/";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto - ReciclaMx Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 500px; margin: 50px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #2e7d32; text-align: center;">Registrar Nueva Recompensa</h2>
        <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">
        
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label><b>Nombre del Producto:</b></label>
                <input type="text" name="nombre" placeholder="Ej: Termo Ecológico" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label><b>Puntos Requeridos:</b></label>
                <input type="number" name="costo_puntos" placeholder="Ej: 500" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label><b>Stock Inicial:</b></label>
                <input type="number" name="stock" placeholder="Cantidad disponible" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label><b>Imagen del Producto:</b></label><br>
                <input type="file" name="imagen" accept="image/*" required style="margin-top: 10px;">
                <p style="font-size: 0.8rem; color: #666;">Se recomienda formato cuadrado (JPG o PNG).</p>
            </div>

            <button type="submit" name="guardar" class="btn-primario" style="width:100%; padding:12px; font-size:1rem; cursor:pointer;">Publicar Recompensa</button>
            
            <a href="admin_recompensas.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Volver al inventario</a>
        </form>
    </div>
</body>
</html>