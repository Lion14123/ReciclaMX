<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>
<header>
    <div class="nav-container">
        <div class="logo-container">
            <img src="img/logo_reciclamx_nav.png" alt="Logo" class="logo-img">
            <span class="brand-name">ReciclaMx</span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="puntos.php">Puntos de Recolección</a></li>
                <li><a href="aprender.php">Aprende a Separar</a></li>

                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <li><a href="agendar.php">Agendar Cita</a></li>
                    <li><a href="recompensas.php">Recompensas</a></li>
                    <li><a href="perfil.php" class="btn-login">Mi Perfil</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>