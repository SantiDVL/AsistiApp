<?php
// Parte de arriba de todas las páginas privadas (menú lateral).
// Antes de incluirlo, cada página define $titulo y $activo.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $titulo; ?> - AsistiApp</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app">

    <aside class="sidebar">
        <div class="brand">✓ AsistiApp</div>
        <nav>
            <a class="nav-item <?php if ($activo == 'inicio') { echo 'active'; } ?>" href="inicio.php">Inicio</a>
            <a class="nav-item <?php if ($activo == 'cursos') { echo 'active'; } ?>" href="cursos.php">Cursos</a>
            <a class="nav-item <?php if ($activo == 'historial') { echo 'active'; } ?>" href="historial.php">Historial</a>
            <a class="nav-item <?php if ($activo == 'cuenta') { echo 'active'; } ?>" href="cuenta.php">Cuenta</a>
        </nav>
    </aside>

    <main class="content">
