<?php
// Detalle de una clase del historial: muestra cada alumno con su estado (Presente / Ausente).
// Es de solo lectura. Se abre desde historial.php con una dirección como:
//   historial_detalle.php?curso=3&fecha=2026-07-30

// Conexión a MySQL y configuración básica de la pantalla
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login; también crea $usuario_id
include "includes/verificar_sesion.php";

// Leemos el curso y la fecha de la dirección.
// (int) convierte el curso a número para que nadie meta texto raro.
// mysqli_real_escape_string() hace seguro el texto de la fecha antes de usarlo en el SQL.
// Si la fecha no es válida, la consulta no encuentra nada y la lista queda vacía.
$curso_id = (int) $_GET["curso"];
$fecha = mysqli_real_escape_string($conexion, $_GET["fecha"]);

// Buscamos el curso para mostrar su nombre y su materia
$sql = "SELECT * FROM cursos WHERE id = $curso_id";
// Guardamos los datos del curso: $curso["nombre"], $curso["materia"]
$curso = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// Asistencia de cada alumno ese día.
// INNER JOIN une cada asistencia con el alumno para poder mostrar su nombre.
$sql = "SELECT alumnos.nombre, asistencias.estado
        FROM asistencias
        INNER JOIN alumnos ON alumnos.id = asistencias.alumno_id
        WHERE asistencias.curso_id = $curso_id AND asistencias.fecha = '$fecha'
        ORDER BY alumnos.nombre";
// Ejecuta la consulta; $resultado guarda una fila por alumno
$resultado = mysqli_query($conexion, $sql);

// Pasamos las filas a un array ($filas) para poder contarlas y después mostrarlas
$filas = array();
$presentes = 0;
$ausentes = 0;
// Recorremos las filas: cada vuelta toma un alumno
while ($fila = mysqli_fetch_assoc($resultado)) {
    $filas[] = $fila;                       // agregamos la fila al final del array
    if ($fila["estado"] == "presente") {
        $presentes++;                       // ++ suma 1 al contador
    } else {
        $ausentes++;
    }
}

// Texto del encabezado: "5° A · Matemática" (si el curso no tiene materia, solo el nombre).
// El punto (.) une textos.
$nombre_curso = $curso["nombre"];
if ($curso["materia"] != "") {
    $nombre_curso .= " · " . $curso["materia"];
}
// Fecha en formato 30/07/2026; si es la de hoy, se le agrega "Hoy · " adelante
$fecha_texto = date("d/m/Y", strtotime($fecha));
if ($fecha == date("Y-m-d")) {
    $fecha_texto = "Hoy · " . $fecha_texto;
}

// Datos que usa includes/header.php: título de la pestaña y opción marcada del menú
$titulo = "Detalle de asistencia";
$activo = "historial";
include "includes/header.php";
?>

<!-- Encabezado: curso, fecha y totales -->
<div class="page-head">
    <div>
        <h2><?php echo $nombre_curso; ?></h2>
        <small><?php echo $fecha_texto; ?> · <?php echo $presentes; ?> presentes · <?php echo $ausentes; ?> ausentes</small>
    </div>
</div>

<!-- Lista de alumnos: una fila por alumno -->
<div class="list">
    <!-- foreach recorre el array $filas: en cada vuelta $fila es un alumno -->
    <?php foreach ($filas as $fila) { ?>
        <div class="row">
            <span class="name"><?php echo $fila["nombre"]; ?></span>

            <!-- Etiqueta verde si estuvo presente, roja si estuvo ausente (estilos en style.css) -->
            <?php if ($fila["estado"] == "presente") { ?>
                <span class="estado estado-presente">Presente</span>
            <?php } else { ?>
                <span class="estado estado-ausente">Ausente</span>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<!-- Botones para volver al historial o ir a la pantalla del curso -->
<div class="btn-row">
    <a class="btn btn-primary" href="historial.php">Volver al historial</a>
    <a class="btn btn-outline" href="curso_detalle.php?id=<?php echo $curso_id; ?>">Ver curso</a>
</div>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>
