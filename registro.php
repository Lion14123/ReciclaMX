<?php
include("conexion.php");

if (isset($_POST['registrar'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_pass = $_POST['confirm_password'];

    if ($_POST['password'] !== $confirm_pass) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, password, tag, puntos) VALUES ('$nombre', '$correo', '$pass', 'usuario', 0)";
        if (mysqli_query($conexion, $sql)) {
            header("Location: login.php?msg=registro_exitoso");
        } else {
            $error = "El correo ya está registrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta - ReciclaMx</title>
    <link rel="stylesheet" href="auth.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="auth-body">
    <div class="auth-container">
        <h1>Crear cuenta</h1>
        <p>Únete a nuestra comunidad de reciclaje</p>

        <form action="registro.php" method="POST">
            <div class="input-group">
                <input type="text" name="nombre" placeholder="Nombre completo" required>
            </div>
            <div class="input-group">
                <input type="email" name="correo" placeholder="Correo electrónico" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            </div>
            <button type="submit" name="registrar" class="btn-submit">CREAR CUENTA</button>
        </form>

        <div class="auth-footer">
            ¿Ya tienes cuenta? <a href="login.php">Ingresa aquí</a>
        </div>
    </div>
</body>
</html>