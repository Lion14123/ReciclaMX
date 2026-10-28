<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Seguridad: Solo el admin con el tag correcto puede entrar
if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 1. Cargar datos del material seleccionado mediante el ID de la URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $res = mysqli_query($conexion, "SELECT * FROM guia_materiales WHERE id = '$id'");
    $m = mysqli_fetch_assoc($res);
}

// 2. Procesar la actualización al presionar el botón
if (isset($_POST['actualizar'])) {
    $id_upd = $_POST['id'];
    $inst = mysqli_real_escape_string($conexion, $_POST['instrucciones']);
    $pts = mysqli_real_escape_string($conexion, $_POST['valor_puntos']);
    
    // Verificamos si el usuario subió una nueva imagen
    if (!empty($_FILES['nueva_imagen']['name'])) {
        $nombre_img = $_FILES['nueva_imagen']['name'];
        $ruta_temporal = $_FILES['nueva_imagen']['tmp_name'];
        $ruta_destino = "img/" . $nombre_img;

        // Movemos el archivo físico a la carpeta img/
        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            // Actualizamos texto e imagen (columna 'imagen' según tu SQL)
            $sql = "UPDATE guia_materiales SET instrucciones='$inst', valor_puntos='$pts', imagen='$ruta_destino' WHERE id='$id_upd'";
        }
    } else {
        // Si no subió foto, solo actualizamos los textos
        $sql = "UPDATE guia_materiales SET instrucciones='$inst', valor_puntos='$pts' WHERE id='$id_upd'";
    }
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_aprender.php?msj=actualizado");
        exit();
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Material - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #2e7d32;">Editar Guía: <?php echo htmlspecialchars($m['titulo']); ?></h2>
        <hr>
        
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
            
            <div style="margin-bottom: 20px;">
                <label><b>Instrucciones de Separación:</b></label>
                <textarea name="instrucciones" rows="5" style="width:100%; padding:10px; margin-top:5px; border-radius:5px; border:1px solid #ccc; font-family: sans-serif;"><?php echo htmlspecialchars($m['instrucciones']); ?></textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label><b>Valor de Puntos (Etiqueta):</b></label>
                <input type="text" name="valor_puntos" value="<?php echo htmlspecialchars($m['valor_puntos']); ?>" style="width:100%; padding:10px; margin-top:5px; border-radius:5px; border:1px solid #ccc;">
            </div>

            <div style="margin-bottom: 25px; padding: 15px; background: #f9f9f9; border-radius: 5px;">
                <label><b>Imagen Actual:</b></label><br>
                <img src="<?php echo $m['imagen']; ?>" width="120" style="border-radius:5px; margin: 10px 0; border: 1px solid #ddd;">
                <br>
                <label><b>Cambiar Foto (Opcional):</b></label><br>
                <input type="file" name="nueva_imagen" accept="image/*" style="margin-top: 5px;">
                <p style="font-size: 0.8rem; color: #666;">Si no seleccionas ningún archivo, se mantendrá la imagen actual.</p>
            </div>

            <button type="submit" name="actualizar" class="btn-primario" style="width:100%; padding:12px; cursor:pointer;">Actualizar Información</button>
            
            <a href="admin_aprender.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancelar y volver</a>
        </form>
    </div>
</body>
</html>