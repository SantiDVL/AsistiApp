<?php
// Elimina el curso indicado, siempre que pertenezca al usuario actual.
// La base de datos elimina también los registros relacionados por ON DELETE CASCADE.
// Carga la conexión y comprueba que haya una sesión iniciada.
include "includes/conexion.php";
include "includes/verificar_sesion.php";

// Lee el identificador del curso y lo convierte a número.
$id = (int) $_POST["id"];

// Elimina el curso solo si su identificador y el dueño coinciden.
$sql = "DELETE FROM cursos WHERE id = $id AND usuario_id = $usuario_id";
mysqli_query($conexion, $sql);

// Vuelve a la lista de cursos y detiene la ejecución.
header("Location: cursos.php");
exit;
?>
