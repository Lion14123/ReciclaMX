<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['usuario_id'];

// Consultar puntos del usuario logueado
$res_u = mysqli_query($conexion, "SELECT puntos FROM usuarios WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($res_u);

// Consultar catálogo de productos
$productos = mysqli_query($conexion, "SELECT * FROM productos WHERE stock > 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Canjear Recompensas - ReciclaMx</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <section id="recompensas">
            <h1>Canjear Recompensas</h1>

            <div class="user-points-panel">
                <p>Tus Puntos Disponibles</p>
                <h2 style="color:white; border:none; font-size:3rem; margin:0;">
                    <?php echo number_format($user['puntos']); ?>
                </h2>
                <p>Sigue reciclando para ganar más.</p>
            </div>

            <div class="recompensas-grid">
                <?php while($prod = mysqli_fetch_assoc($productos)): ?>
                <article class="recompensa-card">
                    <img src="<?php echo $prod['imagen_url']; ?>" alt="Producto" class="img-estandar">
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <p class="puntos-costo"><?php echo $prod['costo_puntos']; ?> puntos</p>
                    <p>Stock: <?php echo $prod['stock']; ?></p>
                    
                    <form action="procesar_canje.php" method="POST">
                        <input type="hidden" name="id_producto" value="<?php echo $prod['id']; ?>">
                        <button type="submit" class="btn-primario" 
                                <?php echo ($user['puntos'] < $prod['costo_puntos']) ? 'disabled style="background:gray;"' : ''; ?>>
                            Canjear
                        </button>
                    </form>
                </article>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
</body>
</html>
<?php if(isset($_GET['msj'])): ?>
<script>
    <?php if($_GET['msj'] == 'exito'): ?>
        Swal.fire({
            title: '¡Canje Exitoso!',
            text: 'Tu recompensa ha sido procesada. Revisa tu correo para los detalles del canje.',
            icon: 'success',
            confirmButtonColor: '#2e7d32',
            confirmButtonText: '¡Excelente!'
        });
    <?php elseif($_GET['msj'] == 'puntos_insuficientes'): ?>
        Swal.fire({
            title: 'Puntos Insuficientes',
            text: 'Aún te faltan puntos para este premio. ¡Sigue reciclando!',
            icon: 'warning',
            confirmButtonColor: '#fbc02d',
            confirmButtonText: 'Entendido'
        });
    <?php elseif($_GET['msj'] == 'error_tecnico'): ?>
        Swal.fire({
            title: 'Error en el sistema',
            text: 'No pudimos procesar el canje en este momento.',
            icon: 'error',
            confirmButtonColor: '#d32f2f',
            confirmButtonText: 'Cerrar'
        });
    <?php endif; ?>
</script>
<?php endif; ?>