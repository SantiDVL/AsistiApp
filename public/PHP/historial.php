<?php
// Pantalla "Historial": lista las clases con asistencia guardada.
// Cada fila es un enlace que abre historial_detalle.php con el detalle de esa clase.

// Conexión a MySQL y configuración básica de la pantalla
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login; también crea $usuario_id
include "includes/verificar_sesion.php";

// Una fila por curso y por día, contando presentes y ausentes.
// Traemos también curso_id y la fecha para armar el enlace al detalle.
//   SUM(estado = 'presente') suma 1 por cada alumno presente (la comparación vale 1 o 0)
//   INNER JOIN une cada asistencia con su curso
//   WHERE deja solo los cursos de este usuario
//   GROUP BY junta todas las asistencias del mismo curso y día en una sola fila
//   ORDER BY muestra primero las fechas más recientes
//   LIMIT 50 trae como máximo 50 filas
$sql = "SELECT asistencias.curso_id, cursos.nombre AS curso, cursos.materia, asistencias.fecha,
               SUM(asistencias.estado = 'presente') AS presentes,
               SUM(asistencias.estado = 'ausente') AS ausentes
        FROM asistencias
        INNER JOIN cursos ON cursos.id = asistencias.curso_id
        WHERE cursos.usuario_id = $usuario_id
        GROUP BY asistencias.curso_id, asistencias.fecha
        ORDER BY asistencias.fecha DESC, cursos.nombre
        LIMIT 50";
// Ejecuta la consulta; $resultado guarda las filas encontradas
$resultado = mysqli_query($conexion, $sql);

// Datos que usa includes/header.php: título de la pestaña y opción marcada del menú
$titulo = "Historial";
$activo = "historial";
include "includes/header.php";
?>

<!-- Encabezado de la página -->
<div class="page-head"><h2>Historial</h2></div>

<!-- Si la consulta no devolvió filas, todavía no se tomó asistencia -->
<?php if (mysqli_num_rows($resultado) == 0) { ?>
    <div class="empty">Todavía no hay asistencias registradas.</div>
<?php } else { ?>
    <!-- Lista con una fila por clase -->
    <div class="list">
        <!-- Repetimos una fila por cada clase que devolvió la base -->
        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <!-- Cada fila es un enlace. La dirección lleva el curso y la fecha,
                 por ejemplo: historial_detalle.php?curso=3&fecha=2026-07-30 -->
            <a class="row row-link"
               href="historial_detalle.php?curso=<?php echo $fila["curso_id"]; ?>&fecha=<?php echo $fila["fecha"]; ?>">
                <!-- date() + strtotime() pasan la fecha de 2026-07-30 a 30/07/2026.
                     trim() saca el espacio sobrante si el curso no tiene materia. -->
                <span class="name"><?php echo date("d/m/Y", strtotime($fila["fecha"])); ?> · <?php echo trim($fila["curso"] . " " . $fila["materia"]); ?></span>
                <!-- Resumen de cuántos estuvieron presentes y ausentes -->
                <small><?php echo $fila["presentes"]; ?> presentes · <?php echo $fila["ausentes"]; ?> ausentes</small>
            </a>
        <?php } ?>
    </div>
<?php } ?>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>
