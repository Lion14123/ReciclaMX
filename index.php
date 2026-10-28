<?php
include("conexion.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Forma segura de preguntar por el tag sin que lance Warnings
if (isset($_SESSION['tag']) && $_SESSION['tag'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReciclaMx - Recicla, entrega, gana.</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <?php if(isset($_SESSION['usuario_id'])): 
            $id_user = $_SESSION['usuario_id'];

// Consultar puntos del usuario logueado
$res_u = mysqli_query($conexion, "SELECT puntos FROM usuarios WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($res_u);
?>
            
    <section class="dashboard-container">
        <div class="welcome-header">
            <h1>¡Qué bueno verte, <?php echo explode(' ', $_SESSION['nombre'])[0]; ?>! ♻️</h1>
            <div class="user-points-panel">
                <p>Tus Puntos Disponibles</p>
                <h2 style="color:white; border:none; font-size:3rem; margin:0;">
                    <?php echo number_format($user['puntos']); ?>
                </h2>
            </div>
        </div>

        <div class="grid-propuesta">
            <article class="card">
                <div class="card-icon">📅</div>
                <div class="card-body">
                    <h3>¿Tienes residuos?</h3>
                    <p>Programa una recolección a domicilio en menos de un minuto e iremos por ella lo antes posible.</p>
                    <a href="agendar.php" class="btn-action-outline" style="color: green;">AGENDAR AHORA <span>🗓️</span></a>
                </div>
            </article>

            <article class="card">
                <div class="card-icon">🎁</div>
                <div class="card-body">
                    <h3>Canjear Puntos</h3>
                    <p>Revisa los premios disponibles que puedes obtener hoy mismo.</p>
                    <a href="recompensas.php" class="btn-action-outline" style="color: green;">VER RECOMPENSAS <span>🎁</span></a>
                </div>
            </article>

            <article class="card">
                <div class="card-icon">📊</div>
                    <h3>Mi Actividad</h3>
                    <p>Consulta el historial de tus reciclajes o bien puedes ver tu perfil.</p>
                    <a href="perfil.php" class="btn-action-outline" style="color: green;">IR A MI PERFIL <span>📈</span></a>
            </article>
        </div>
    </section>
<?php else: ?>
    <section class="hero">
            <img src="img/mapa_reciclaje_grande.png" alt="ReciclaMx México" class="hero-logo">
            <h1>ReciclaMx</h1>
            <p class="slogan">Recicla, entrega, gana.</p>
            <p class="introduccion">Transformamos tus residuos en oportunidades para ti y para el planeta.</p>
            <br>
            <a href="login.php" class="btn-primario">¡Empieza a ganar hoy!</a>
        </section>
    <?php endif; ?>

        <section id="propuesta">
            <h2>Nuestra Misión</h2>
            <p>En ReciclaMx, facilitamos el reciclaje recompensando tu esfuerzo. Somos un puente tecnológico que conecta a ciudadanos responsables como tú con centros de procesamiento de residuos, asegurando que materiales valiosos no terminen en vertederos.</p>
            
            <div class="grid-propuesta">
                <article class="card">
                    <div class="card-icon">♻️</div>
                    <h3>¿Qué hacemos?</h3>
                    <p>Recolectamos tus residuos reciclables (PET, papel, cartón, electrónicos y más) directamente en tu domicilio o en nuestros puntos estratégicos.</p>
                </article>

                <article class="card">
                    <div class="card-icon">🎁</div>
                    <h3>Propuesta de Valor</h3>
                    <p>Convertimos tu basura en "Puntos Recicla", canjeables por recompensas reales: tarjetas de regalo, descuentos en servicios y productos ecológicos.</p>
                </article>

                <article class="card">
                    <div class="card-icon">🚀</div>
                    <h3>Nuestro Diferenciador</h3>
                    <p>Ofrecemos la máxima comodidad con recolección a domicilio programada y total transparencia en el pesaje y valor de tus residuos, todo gestionado desde nuestra plataforma.</p>
                </article>
            </div>

            <?php if(!isset($_SESSION['usuario_id'])): ?>
    <br>
    <h2 style="text-align: center; margin-top: 3rem;">¿Cómo empezar con ReciclaMx?</h2>
    <p style="text-align: center; color: #555;">Tu camino hacia un mundo más limpio empieza aquí.</p>
    
    <div class="grid-propuesta" style="margin-top: 2rem;">
        <article class="card highlight">
            <div class="card-icon">📝</div>
            <h3>Regístrate</h3>
            <p>Crea tu cuenta en segundos para empezar a acumular puntos por cada kilo de material que recicles.</p>
            <a href="registro.php" class="btn-secundario" style="color: green;">Crear mi cuenta</a>
        </article>

        <article class="card highlight">
            <div class="card-icon">📍</div>
            <h3>Ubica Centros</h3>
            <p>¿Prefieres entregarlo tú mismo? Conoce nuestros puntos de recolección aliados cerca de tu domicilio.</p>
            <a href="puntos.php" class="btn-secundario" style="color: green;">Ver Mapa</a>
        </article>

        <article class="card highlight">
            <div class="card-icon">📖</div>
            <h3>Aprende a Separar</h3>
            <p>¿No sabes si ese envase es reciclable? Consulta nuestra guía rápida de separación de residuos.</p>
            <a href="aprender.php" class="btn-secundario" style="color: green;">Ver Guía</a>
        </article>
    </div>
<?php endif; ?>

    </main>

    <footer>
        <p>&copy; 2023 ReciclaMx. Todos los derechos reservados.</p>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(isset($_SESSION['nombre'])): ?>
<script>
    // Solo mostramos el saludo una vez por sesión
    if (!sessionStorage.getItem('bienvenidaMostrada')) {
        Swal.fire({
            title: '¡Hola, <?php echo $_SESSION['nombre']; ?>!',
            text: 'Has iniciado sesión correctamente.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        sessionStorage.setItem('bienvenidaMostrada', 'true');
    }
</script>
<?php endif; ?>
</body>
</html>

