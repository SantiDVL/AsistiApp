<?php
// Recibe el formulario de alumno_nuevo.php y guarda el alumno (INSERT).
// No muestra nada: guarda y redirige a la pantalla del curso.

// Conexión a MySQL y configuración básica del formulario
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login
include "includes/verificar_sesion.php";

// Leemos los datos del formulario ($_POST). trim() saca los espacios del principio y del final.
// mysqli_real_escape_string() prepara el texto para el SQL: sin esto, un nombre con
// apóstrofe (por ejemplo D'Angelo) rompería la consulta.
$nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
$dni = mysqli_real_escape_string($conexion, trim($_POST["dni"]));
$correo_alumno = mysqli_real_escape_string($conexion, trim($_POST["correo_alumno"]));
// (int) convierte el curso a número
$curso_id = (int) $_POST["curso_id"];

// Guardamos el alumno nuevo (INSERT agrega una fila a la tabla alumnos)
$sql = "INSERT INTO alumnos (curso_id, nombre, dni, correo_alumno)
        VALUES ($curso_id, '$nombre', '$dni', '$correo_alumno')";
mysqli_query($conexion, $sql);

// Volvemos a la pantalla del curso, donde ya aparece el alumno nuevo
header("Location: curso_detalle.php?id=" . $curso_id);
// exit corta la ejecución para que no siga corriendo código después de redirigir
exit;
?>
