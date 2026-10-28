<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['tag']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php"); exit();
}

if (isset($_POST['guardar'])) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $desc = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $inst = mysqli_real_escape_string($conexion, $_POST['instrucciones']);
    $pts = mysqli_real_escape_string($conexion, $_POST['valor_puntos']);

    // Manejo de la Imagen
    $nombre_img = $_FILES['imagen']['name'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $ruta_destino = "img/" . $nombre_img;

    if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
        $sql = "INSERT INTO guia_materiales (titulo, descripcion, instrucciones, imagen, valor_puntos) 
                VALUES ('$titulo', '$desc', '$inst', '$ruta_destino', '$pts')";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: admin_aprender.php?msj=creado");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Material - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2>Dar de Alta Nuevo Material</h2>
        <form method="POST" enctype="multipart/form-data">
            <label><b>Título del Material:</b></label>
            <input type="text" name="titulo" required style="width:100%; padding:10px; margin-bottom:15px;">

            <label><b>Descripción Breve:</b></label>
            <input type="text" name="descripcion" required style="width:100%; padding:10px; margin-bottom:15px;">
            
            <label><b>Instrucciones de Separación:</b></label>
            <textarea name="instrucciones" rows="4" style="width:100%; padding:10px; margin-bottom:15px;"></textarea>
            
            <label><b>Valor en Puntos:</b></label>
            <input type="text" name="valor_puntos" placeholder="Ej: Valor alto" style="width:100%; padding:10px; margin-bottom:15px;">

            <label><b>Imagen del Material:</b></label>
            <input type="file" name="imagen" accept="image/*" required style="margin-bottom:20px;">

            <button type="submit" name="guardar" class="btn-primario" style="width:100%;">Crear Apartado</button>
            <a href="admin_aprender.php" style="display:block; text-align:center; margin-top:15px;">Cancelar</a>
        </form>
    </div>
</body>
</html>