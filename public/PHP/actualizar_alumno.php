<?php
// Recibe el formulario de alumno_editar.php y modifica el alumno (UPDATE).
// No muestra nada: guarda y redirige al perfil del alumno.

// Conexión a MySQL y configuración básica del formulario
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login
include "includes/verificar_sesion.php";

// (int) convierte a número el id del alumno y el del curso
$id = (int) $_POST["id"];
$curso_id = (int) $_POST["curso_id"];
// trim() saca espacios sobrantes; mysqli_real_escape_string() prepara el texto para el SQL
// (sin esto, un nombre con apóstrofe como D'Angelo rompería la consulta)
$nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
$dni = mysqli_real_escape_string($conexion, trim($_POST["dni"]));
$correo_alumno = mysqli_real_escape_string($conexion, trim($_POST["correo_alumno"]));

// Modificamos al alumno (UPDATE cambia los datos de una fila que ya existe)
$sql = "UPDATE alumnos
        SET nombre = '$nombre', dni = '$dni', correo_alumno = '$correo_alumno', curso_id = $curso_id
        WHERE id = $id";
mysqli_query($conexion, $sql);

// Volvemos al perfil del alumno, donde ya se ven los cambios
header("Location: alumno.php?id=" . $id);
// exit corta la ejecución para que no siga corriendo código después de redirigir
exit;
?>
