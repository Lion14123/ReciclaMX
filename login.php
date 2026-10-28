<?php
include("conexion.php");
session_start();

if (isset($_POST['ingresar'])) {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $pass = $_POST['password'];

    // 1. Buscamos al usuario por correo
    $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $result = mysqli_query($conexion, $query);
    $user = mysqli_fetch_assoc($result);

    // 2. Verificamos si el usuario existe y la contraseña es correcta
    if ($user && password_verify($pass, $user['password'])) {
        
        // --- NUEVA VALIDACIÓN DE ESTADO ---
        // Si el estado no es 'activo', detenemos el login
        if (isset($user['estado']) && $user['estado'] === 'inactivo') {
            $error = "Tu cuenta ha sido inhabilitada. Por favor, contacta al administrador.";
        } else {
            // GUARDAMOS LOS DATOS EN LA SESIÓN
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['puntos'] = $user['puntos'];
            $_SESSION['tag'] = $user['tag']; 

            // Redirección basada en el rango (tag)
            if ($_SESSION['tag'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ReciclaMx | Acceso</title>
    <link rel="stylesheet" href="auth.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="auth-body">
    <div class="auth-container">
        <img src="img/logo_reciclamx_nav.png" alt="Logo" style="width: 80px; margin-bottom: 15px;">
        <h1>Bienvenido</h1>
        
        <?php if(isset($error)): ?>
            <div style="background: #ffcdd2; color: #b71c1c; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                <?php echo $error; ?>
            </div>
        <?php else: ?>
            <p>Ingresa tus datos para entrar al sistema</p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="input-group">
                <input type="email" name="correo" placeholder="Correo electrónico" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" name="ingresar" class="btn-submit">INGRESAR</button>
        </form>

        <div class="auth-footer">
            ¿Nuevo aquí? <a href="registro.php">Crea una cuenta</a>
        </div>
    </div>
    <?php if(isset($_GET['msg']) && $_GET['msg'] === 'registro_exitoso'): ?>
<script>
    Swal.fire({
        title: '¡Registro Exitoso!',
        text: 'Tu cuenta ha sido creada. Ya puedes iniciar sesión.',
        icon: 'success',
        confirmButtonColor: '#2e7d32', // Un verde acorde al reciclaje
        confirmButtonText: 'Genial'
    });
</script>
<?php endif; ?>
</body>
</html>