<?php
// Conecta con la base de datos (crea la variable $conexion) y trae las funciones comunes (escapar, mostrar_error)
include "includes/conexion.php";
// Si no hay sesión iniciada manda al login; si la hay, crea $usuario_id con el número del docente
include "includes/verificar_sesion.php";

// Contamos cuántos alumnos quedaron presentes y ausentes hoy en este curso
$id = (int) $_GET["id"];
// Fecha de hoy con formato año-mes-día
$hoy = date("Y-m-d");
// SUM(estado = 'presente') suma 1 por cada alumno presente; igual con "ausente".
// WHERE limita el conteo a este curso y al día de hoy.
$sql = "SELECT SUM(estado = 'presente') AS presentes, SUM(estado = 'ausente') AS ausentes
        FROM asistencias WHERE curso_id = $id AND fecha = '$hoy'";
// Ejecuta la consulta y toma la fila con los dos totales
$conteo = mysqli_fetch_assoc(mysqli_query($conexion, $sql));
// (int) convierte el resultado en número (si no hubiera registros, queda en 0)
$presentes = (int) $conteo["presentes"];
$ausentes = (int) $conteo["ausentes"];

// Datos que usa header.php: $titulo es el texto de la pestaña y $activo marca la opción seleccionada del menú
$titulo = "Asistencia guardada";
$activo = "cursos";
// Dibuja la parte de arriba de la página (la estructura y el menú lateral)
include "includes/header.php";
?>

<!-- Caja centrada con la confirmación -->
<div class="ok-box">
    <!-- Círculo con el tilde -->
    <div class="modal-icon">✓</div>
    <h2>¡Asistencia guardada!</h2>

    <!-- Dos tarjetas con la cantidad de presentes y ausentes de hoy -->
    <div class="grid-2">
        <div class="card"><div class="stat-label">Presentes</div><div class="stat"><?php echo $presentes; ?></div></div>
        <div class="card"><div class="stat-label">Ausentes</div><div class="stat"><?php echo $ausentes; ?></div></div>
    </div>

    <!-- Botones: volver al inicio o ver el historial -->
    <a class="btn btn-primary btn-block" href="inicio.php">Volver al inicio</a>
    <a class="btn btn-outline btn-block" href="historial.php">Ver historial</a>
</div>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>
