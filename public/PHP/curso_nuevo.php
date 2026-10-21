<?php
// Carga la conexión con la base y comprueba que el usuario haya iniciado sesión.
include "includes/conexion.php";
include "includes/verificar_sesion.php";

// Busca la institución del usuario para proponerla como valor inicial del campo.
$sql = "SELECT institucion FROM usuarios WHERE id = $usuario_id";
$usuario = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// Define el título y la sección activa del encabezado.
$titulo = "Nuevo curso";
$activo = "cursos";
include "includes/header.php";
?>

<!-- Título del formulario para crear un curso. -->
<div class="page-head"><h2>Nuevo curso</h2></div>

<!-- Envía los datos al archivo que guarda el curso en la base de datos. -->
<form class="form-card" action="guardar_curso.php" method="post">
    <div class="form-grid" style="grid-template-columns:1fr">
        <!-- Nombre del curso (obligatorio). -->
        <div class="field">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="3°A" required>
        </div>
        <!-- Materia del curso. -->
        <div class="field">
            <label for="materia">Materia</label>
            <input type="text" id="materia" name="materia" placeholder="Matemática">
        </div>
        <!-- Institución sugerida según los datos de la cuenta. -->
        <div class="field">
            <label for="institucion">Institución</label>
            <input type="text" id="institucion" name="institucion" value="<?php echo escapar($usuario["institucion"]); ?>">
        </div>
    </div>
    <!-- Botones para guardar el curso o cancelar y volver a la lista. -->
    <div class="btn-row">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline" href="cursos.php">Cancelar</a>
    </div>
</form>

<!-- Incluye el pie de página compartido. -->
<?php include "includes/footer.php"; ?>
