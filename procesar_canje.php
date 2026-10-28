<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Verificación de seguridad
if (!isset($_SESSION['usuario_id']) || !isset($_POST['id_producto'])) {
    header("Location: recompensas.php");
    exit();
}

$user_id = $_SESSION['usuario_id'];
$prod_id = $_POST['id_producto'];

// 2. Obtener datos del producto y del usuario
$query_prod = mysqli_query($conexion, "SELECT nombre, costo_puntos, stock FROM productos WHERE id = '$prod_id'");
$producto = mysqli_fetch_assoc($query_prod);

$query_user = mysqli_query($conexion, "SELECT puntos FROM usuarios WHERE id = '$user_id'");
$usuario = mysqli_fetch_assoc($query_user);

// 3. Validaciones finales antes de procesar
if ($producto['stock'] > 0 && $usuario['puntos'] >= $producto['costo_puntos']) {
    
    // INICIAR TRANSACCIÓN
    mysqli_begin_transaction($conexion);

    try {
        // A. Restar puntos al usuario
        mysqli_query($conexion, "UPDATE usuarios SET puntos = puntos - {$producto['costo_puntos']} WHERE id = '$user_id'");

        // B. Restar stock al producto (Esto activará tu TRIGGER de stock bajo automáticamente)
        mysqli_query($conexion, "UPDATE productos SET stock = stock - 1 WHERE id = '$prod_id'");

        // C. Registrar en el historial
        $puntos = $producto['costo_puntos'];
        mysqli_query($conexion, "INSERT INTO historial_canjes (usuario_id, producto_id, puntos_canjeados) VALUES ('$user_id', '$prod_id', '$puntos')");

        // SI TODO SALIÓ BIEN, GUARDAR CAMBIOS
        mysqli_commit($conexion);
        header("Location: recompensas.php?msj=exito");

    } catch (Exception $e) {
        // SI ALGO FALLA, DESHACER TODO
        mysqli_rollback($conexion);
        header("Location: recompensas.php?msj=error_tecnico");
    }

} else {
    header("Location: recompensas.php?msj=puntos_insuficientes");
}
?>