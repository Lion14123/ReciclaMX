<?php
include("conexion.php");

// Consultar la guía de materiales desde la base de datos
$query = "SELECT * FROM guia_materiales";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReciclaMx - Aprender a Reciclar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <section id="aprender">
            <h1>Guía Detallada de Separación</h1>
            <p style="text-align:center; margin-bottom:2rem;">Conoce qué residuos aceptamos y cómo prepararlos para recibir más puntos.</p>

            <div class="guia-residuos">
                
                <?php 
                // Ciclo para mostrar cada material registrado
                while ($material = mysqli_fetch_assoc($resultado)): 
                ?>
                    <article class="residuo-articulo">
                        <img src="<?php echo htmlspecialchars($material['imagen']); ?>" 
                             alt="<?php echo htmlspecialchars($material['titulo']); ?>" 
                             class="img-estandar">
                        <div>
                            <h3><?php echo htmlspecialchars($material['titulo']); ?></h3>
                            <p><strong>Qué es:</strong> <?php echo htmlspecialchars($material['descripcion']); ?></p>
                            <p><strong>Cómo entregar:</strong> <?php echo htmlspecialchars($material['instrucciones']); ?></p>
                            <span class="puntos-costo"><?php echo htmlspecialchars($material['valor_puntos']); ?></span>
                        </div>
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