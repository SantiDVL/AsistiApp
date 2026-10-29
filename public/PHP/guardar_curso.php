<?php
// =====================================================
// guardar_curso.php: recibe el formulario de curso_nuevo.php y guarda el curso (INSERT).
// No muestra pantalla: guarda y redirige al detalle del curso nuevo.
// =====================================================

// Conexión a la base de datos y funciones comunes
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login; también crea $usuario_id
include "includes/verificar_sesion.php";

// Leemos los datos del formulario ($_POST). trim() saca espacios sobrantes y
// mysqli_real_escape_string() prepara el texto para el SQL (evita que comillas rompan la consulta).
$nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
$materia = mysqli_real_escape_string($conexion, trim($_POST["materia"]));
$institucion = mysqli_real_escape_string($conexion, trim($_POST["institucion"]));

// Guardamos el curso nuevo (INSERT agrega una fila a la tabla cursos).
// $usuario_id es el docente que inició sesión: así el curso queda asociado a él.
$sql = "INSERT INTO cursos (usuario_id, nombre, materia, institucion)
        VALUES ($usuario_id, '$nombre', '$materia', '$institucion')";
mysqli_query($conexion, $sql);

// Vamos al detalle del curso que acabamos de crear.
// mysqli_insert_id() devuelve el número (id) que MySQL le asignó al curso recién guardado.
$nuevo_id = mysqli_insert_id($conexion);
header("Location: curso_detalle.php?id=" . $nuevo_id);
// exit corta la página para que no siga ejecutando código
exit;
?>
