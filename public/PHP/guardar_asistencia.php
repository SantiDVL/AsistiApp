<?php
// =====================================================
// guardar_asistencia.php: recibe el formulario de asistencia.php
// y guarda un registro de asistencia por cada alumno del curso.
// No muestra pantalla: guarda y redirige a asistencia_ok.php.
// =====================================================

// Conexión a la base de datos y funciones comunes
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login
include "includes/verificar_sesion.php";

// Número del curso (viene en un campo oculto del formulario). (int) lo convierte a número.
$curso_id = (int) $_POST["curso_id"];
// Fecha de hoy con formato año-mes-día (el formato que usa MySQL para las fechas)
$hoy = date("Y-m-d");

// $_POST["estado"] es un array donde cada alumno tiene su estado:
//   [id_del_alumno] => "presente" o "ausente"
// Ejemplo: $estados[5] = "ausente" significa que el alumno 5 estuvo ausente.
$estados = $_POST["estado"];

// Buscamos los alumnos del curso para recorrerlos uno por uno
$sql = "SELECT id FROM alumnos WHERE curso_id = $curso_id";
$alumnos = mysqli_query($conexion, $sql);

// while repite el bloque mientras queden alumnos: cada vuelta toma el siguiente
while ($alumno = mysqli_fetch_assoc($alumnos)) {
    // Número del alumno de esta vuelta
    $alumno_id = $alumno["id"];
    // Su estado ("presente" o "ausente"), sacado del array del formulario
    $estado = $estados[$alumno_id];

    // Guardamos la asistencia de hoy de este alumno.
    // ON DUPLICATE KEY UPDATE: la tabla no permite dos registros del mismo alumno el mismo día.
    // Si ya había uno (porque se tomó lista antes hoy), en vez de dar error se actualiza su
    // estado; si no había, se crea uno nuevo.
    $sql = "INSERT INTO asistencias (alumno_id, curso_id, fecha, estado)
            VALUES ($alumno_id, $curso_id, '$hoy', '$estado')
            ON DUPLICATE KEY UPDATE estado = '$estado'";
    mysqli_query($conexion, $sql);
}

// Vamos a la pantalla de "Asistencia guardada", pasando el curso en la dirección
header("Location: asistencia_ok.php?id=" . $curso_id);
// exit corta la página para que no siga ejecutando código
exit;
?>
