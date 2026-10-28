<?php
include("conexion.php");
include("admin_auth.php");

$id_user = mysqli_real_escape_string($conexion, $_GET['id']);
$res = mysqli_query($conexion, "SELECT nombre, puntos FROM usuarios WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($res);

if (isset($_POST['procesar'])) {
    $cantidad = intval($_POST['cantidad']);
    $accion = $_POST['accion']; // 'sumar' o 'restar'

    if ($accion === 'sumar') {
        $sql = "UPDATE usuarios SET puntos = puntos + $cantidad WHERE id = '$id_user'";
    } else {
        // Al restar, nos aseguramos de que los puntos no queden en negativo si así lo deseas, 
        // aunque para casos de fraude puede ser necesario dejar el saldo negativo.
        $sql = "UPDATE usuarios SET puntos = puntos - $cantidad WHERE id = '$id_user'";
    }
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_usuarios.php?msj=puntos_actualizados");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Puntos - ReciclaMx</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 450px; margin: 80px auto; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); text-align: center;">
        <h2 style="color: #2e7d32;">Gestionar Puntos</h2>
        <p>Usuario: <strong><?php echo htmlspecialchars($user['nombre']); ?></strong></p>
        <p>Saldo actual: <span style="color: #fb8c00; font-weight: bold;"><?php echo $user['puntos']; ?> pts</span></p>
        
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px; display: flex; justify-content: center; gap: 20px;">
                <label>
                    <input type="radio" name="accion" value="sumar" checked> Sumar
                </label>
                <label>
                    <input type="radio" name="accion" value="restar"> Restar (Fraude)
                </label>
            </div>

            <label>Cantidad de puntos:</label>
            <input type="number" name="cantidad" required min="1" placeholder="Ej: 500" 
                   style="width: 100%; padding: 12px; margin: 15px 0; border: 2px solid #2e7d32; border-radius: 8px; font-size: 1.2rem; text-align: center;">
            
            <button type="submit" name="procesar" class="btn-primario" style="width: 100%; padding: 12px; cursor: pointer;">Confirmar Operación</button>
            <a href="admin_usuarios.php" style="display: block; margin-top: 15px; color: #666; text-decoration: none;">Cancelar</a>
        </form>
    </div>
</body>
</html>