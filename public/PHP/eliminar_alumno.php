<?php
// =====================================================
// eliminar_alumno.php: borra un alumno (DELETE).
// Sus asistencias también se borran solas (ON DELETE CASCADE en database.sql).
// =====================================================

// Conexión a la base de datos y funciones comunes
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login
include "includes/verificar_sesion.php";

// Número del alumno a borrar (viene del formulario del botón "Eliminar"). (int) lo convierte a número.
$id = (int) $_POST["id"];

// Antes de borrarlo, buscamos de qué curso era, para poder volver a esa pantalla después.
// Se hace antes porque, una vez borrado, ya no se puede consultar.
$sql = "SELECT curso_id FROM alumnos WHERE id = $id";
$alumno = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// DELETE FROM borra la fila de la tabla alumnos cuyo id coincide
mysqli_query($conexion, "DELETE FROM alumnos WHERE id = $id");

// Volvemos a la pantalla del curso al que pertenecía el alumno
header("Location: curso_detalle.php?id=" . $alumno["curso_id"]);
// exit corta la página para que no siga ejecutando código
exit;
?>
