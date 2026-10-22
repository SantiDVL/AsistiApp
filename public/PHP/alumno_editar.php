<?php
// Pantalla con el formulario para editar un alumno que ya existe.
// Los datos se envían a actualizar_alumno.php, que los guarda en la base.

// Conexión a MySQL y funciones comunes (escapar, mostrar_error)
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login; también crea $usuario_id
include "includes/verificar_sesion.php";

// Número de alumno que viene en la dirección (alumno_editar.php?id=5).
// (int) lo convierte a número para que nadie pueda meter texto raro.
$id = (int) $_GET["id"];

// Buscamos al alumno por su número
$sql = "SELECT * FROM alumnos WHERE id = $id";
// Guardamos los datos del alumno en un array: $alumno["nombre"], $alumno["dni"], etc.
$alumno = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// Cursos del usuario para armar el desplegable (el alumno se puede cambiar de curso)
$sql = "SELECT id, nombre FROM cursos WHERE usuario_id = $usuario_id ORDER BY nombre";
$cursos = mysqli_query($conexion, $sql);

// Datos que usa includes/header.php: título de la pestaña y opción marcada del menú
$titulo = "Editar alumno";
$activo = "cursos";
include "includes/header.php";
?>

<!-- Encabezado de la página -->
<div class="page-head"><h2>Editar alumno</h2></div>

<!-- Al enviar, los datos viajan por POST a actualizar_alumno.php -->
<form class="form-card" action="actualizar_alumno.php" method="post">
    <!-- Campo oculto: lleva el número del alumno para que actualizar_alumno.php sepa a cuál modificar -->
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="form-grid">
        <!-- Nombre: value= muestra el nombre actual; "required" impide dejarlo vacío -->
        <div class="field">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo escapar($alumno["nombre"]); ?>" required
                   oninput="soloLetras(this)"
                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                   title="Ingresá solo letras y espacios">
        </div>

        <!-- DNI: solo números (mismas reglas que en alumno_nuevo.php):
             - oninput="soloNumeros(this)" borra al instante lo que no sea número (app.js)
             - inputmode="numeric" abre el teclado numérico en el celular
             - pattern y maxlength limitan a 1 a 8 dígitos
             - title es el mensaje si no cumple -->
        <div class="field">
            <label for="dni">DNI</label>
            <input type="text" id="dni" name="dni" value="<?php echo escapar($alumno["dni"]); ?>"
                   oninput="soloNumeros(this)"
                   inputmode="numeric" pattern="[0-9]{1,8}" maxlength="8"
                   title="Ingresá solo números y como máximo 8 dígitos">
        </div>

        <!-- Curso: desplegable con el curso actual del alumno marcado -->
        <div class="field">
            <label for="curso_id">Curso</label>
            <select id="curso_id" name="curso_id">
                <!-- Una <option> por cada curso del usuario -->
                <?php while ($c = mysqli_fetch_assoc($cursos)) { ?>
                    <!-- "selected" marca el curso al que pertenece hoy el alumno -->
                    <option value="<?php echo $c["id"]; ?>" <?php if ($c["id"] == $alumno["curso_id"]) { echo "selected"; } ?>>
                        <?php echo escapar($c["nombre"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Correo del alumno, con el valor actual -->
        <div class="field">
            <label for="correo_alumno">Correo del alumno</label>
            <input type="email" id="correo_alumno" name="correo_alumno" value="<?php echo escapar($alumno["correo_alumno"]); ?>">
        </div>
    </div>

    <!-- Guardar envía el formulario; Cancelar vuelve al perfil del alumno sin cambiar nada -->
    <div class="btn-row">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline" href="alumno.php?id=<?php echo $id; ?>">Cancelar</a>
    </div>
</form>

<?php include "includes/footer.php"; ?>
