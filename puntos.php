<?php
include("conexion.php");
// Consultar todos los centros de acopio registrados
$query = "SELECT * FROM centros_acopio";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReciclaMx - Puntos de Recolección</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <section id="puntos">
            <h1>Puntos de Recolección</h1>
            
            <div class="mapa-enorme">
                <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3732.123456!2d-101.684!3d21.123456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842b9f123456789%3A0xabcdef123456789!2sLeón%2C%20Guanajuato!5e0!3m2!1ses!2smx!4v1700000000000"
                width="1200" height="490" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <h2>Centros de Acopio Cercanos</h2>
            <div class="info-puntos">
                
                <?php 
                // Iteramos sobre los resultados de la base de datos
                while ($punto = mysqli_fetch_assoc($resultado)): 
                ?>
                    <article class="punto-card">
                        <h3><?php echo htmlspecialchars($punto['nombre']); ?></h3>
                        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($punto['direccion']); ?></p>
                        <p><strong>Horario:</strong> <?php echo htmlspecialchars($punto['horario']); ?></p>
                        <p><strong>Materiales:</strong> <?php echo htmlspecialchars($punto['materiales']); ?></p>
                    </article>
                <?php endwhile; ?>

            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 ReciclaMx. Todos los derechos reservados.</p>
    </footer>

</body>
</html>