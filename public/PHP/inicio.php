<?php
include "includes/conexion.php";
include "includes/verificar_sesion.php";

// Cantidad de cursos del usuario
$sql = "SELECT COUNT(*) AS total FROM cursos WHERE usuario_id = $usuario_id";
$fila = mysqli_fetch_assoc(mysqli_query($conexion, $sql));
$total_cursos = $fila["total"];

// Cantidad de alumnos (de todos sus cursos)
$sql = "SELECT COUNT(*) AS total FROM alumnos
        INNER JOIN cursos ON cursos.id = alumnos.curso_id
        WHERE cursos.usuario_id = $usuario_id";
$fila = mysqli_fetch_assoc(mysqli_query($conexion, $sql));
$total_alumnos = $fila["total"];

$titulo = "Inicio";
$activo = "inicio";
include "includes/header.php";
?>

<div class="page-head">
    <h2>Hola, <?php echo $_SESSION["usuario_nombre"]; ?> 👋</h2>
</div>

<div class="grid-2">
    <a class="card" href="cursos.php">
        <img src="img/book.svg" alt="Cursos">
        <div class="stat"><?php echo $total_cursos; ?></div>
        <div class="stat-label">Cursos</div>
    </a>
    <div class="card">
        <img src="img/users.svg" alt="Alumnos">
        <div class="stat"><?php echo $total_alumnos; ?></div>
        <div class="stat-label">Alumnos</div>
    </div>
</div>

<h3 class="section-title">Accesos rápidos</h3>
<div class="btn-row sin-margen">
    <a class="btn btn-primary" href="cursos.php?tomar=1">Tomar asistencia</a>
    <a class="btn btn-outline" href="curso_nuevo.php">Crear curso</a>
</div>

<?php include "includes/footer.php"; ?>
