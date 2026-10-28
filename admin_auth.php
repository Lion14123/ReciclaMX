<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Si no es admin, lo mandamos al inicio
if (!isset($_SESSION['usuario_id']) || $_SESSION['tag'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>