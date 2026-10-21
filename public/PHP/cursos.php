<?php
// Carga la conexión a la base de datos y comprueba la sesión del usuario.
include "includes/conexion.php";
include "includes/verificar_sesion.php";

// Busca los cursos del usuario y cuenta los alumnos que pertenecen a cada curso.
$sql = "SELECT cursos.*, COUNT(alumnos.id) AS cantidad
        FROM cursos
        LEFT JOIN alumnos ON alumnos.curso_id = cursos.id
        WHERE cursos.usuario_id = $usuario_id
        GROUP BY cursos.id
        ORDER BY cursos.nombre";
// Ejecuta la consulta para mostrar los resultados en la página.
$resultado = mysqli_query($conexion, $sql);

// Comprueba si se abrió esta lista para elegir un curso y tomar asistencia.
$tomar = isset($_GET["tomar"]);

// Define los datos que el encabezado común necesita.
$titulo = "Cursos";
$activo = "cursos";
include "includes/header.php";
?>

<!-- Muestra el título, la cantidad de cursos y el enlace para crear uno nuevo. -->
<div class="page-head">
    <div>
        <!-- Cambia el título según se haya abierto la lista para tomar asistencia. -->
        <h2><?php if ($tomar) { echo "Elegí un curso"; } else { echo "Cursos"; } ?></h2>
        <small><?php echo mysqli_num_rows($resultado); ?> cursos</small>
    </div>
    <a class="btn btn-primary" href="curso_nuevo.php">+ Nuevo curso</a>
</div>

<!-- Si no hay cursos, muestra un aviso; en caso contrario, muestra la lista. -->
<?php if (mysqli_num_rows($resultado) == 0) { ?>
    <div class="empty">Todavía no creaste ningún curso.</div>
<?php } else { ?>
    <div class="grid-2">
        <!-- Recorre los cursos y prepara un enlace según el propósito de la pantalla. -->
        <?php while ($curso = mysqli_fetch_assoc($resultado)) { ?>
            <?php
            // Al elegir para asistencia, abre asistencia.php; en los demás casos, el detalle.
            if ($tomar) {
                $destino = "asistencia.php?id=" . $curso["id"];
            } else {
                $destino = "curso_detalle.php?id=" . $curso["id"];
            }
            ?>
            <!-- Tarjeta con el nombre del curso, su materia y la cantidad de alumnos. -->
            <a class="card" href="<?php echo $destino; ?>">
                <h3><?php echo escapar($curso["nombre"]); ?></h3>
                <small><?php echo escapar($curso["materia"]); ?> · <?php echo $curso["cantidad"]; ?> alumnos</small>
            </a>
        <?php } ?>
    </div>
<?php } ?>

<!-- Incluye el pie de página común. -->
<?php include "includes/footer.php"; ?>
