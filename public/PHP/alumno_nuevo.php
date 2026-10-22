<?php
// Pantalla con el formulario para agregar un alumno.
// Los datos se envían a guardar_alumno.php, que los guarda en la base.

// Conexión a MySQL y configuración básica de la pantalla
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login y corta la página; también crea $usuario_id
include "includes/verificar_sesion.php";

// Curso que viene preseleccionado (si llegamos desde la pantalla de un curso).
// Empieza en 0, que significa "ninguno elegido".
$curso_elegido = 0;
// isset() comprueba que el dato exista en la dirección (alumno_nuevo.php?curso=3)
if (isset($_GET["curso"])) {
    // (int) lo convierte a número: así nadie puede meter texto raro en la dirección
    $curso_elegido = (int) $_GET["curso"];
}

// Pedimos a la base los cursos de este usuario para armar el desplegable
$sql = "SELECT id, nombre FROM cursos WHERE usuario_id = $usuario_id ORDER BY nombre";
$cursos = mysqli_query($conexion, $sql);

// Datos que usa includes/header.php: el título de la pestaña y la opción marcada del menú
$titulo = "Nuevo alumno";
$activo = "cursos";
// Dibuja la parte de arriba de la página (menú lateral)
include "includes/header.php";
?>

<!-- Encabezado de la página -->
<div class="page-head"><h2>Nuevo alumno</h2></div>

<!-- Al enviar el formulario, los datos viajan por POST a guardar_alumno.php -->
<form class="form-card" action="guardar_alumno.php" method="post">
    <!-- Cuadrícula de dos columnas con los campos -->
    <div class="form-grid">

        <!-- Nombre: "required" impide enviar el formulario si está vacío -->
        <div class="field">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" required
                   oninput="soloLetras(this)"
                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                   title="Ingresá solo letras y espacios">
        </div>

        <!-- DNI: solo números. Se controla en tres capas:
             - oninput="soloNumeros(this)": borra al instante cualquier carácter que no sea número
               (función de app.js; también funciona al pegar)
             - inputmode="numeric": en el celular abre el teclado numérico
             - pattern="[0-9]{1,8}": el navegador solo deja enviar de 1 a 8 dígitos
             - maxlength="8": no deja escribir más de 8 caracteres
             - title: mensaje que se muestra si el DNI no cumple el pattern -->
        <div class="field">
            <label for="dni">DNI</label>
            <input
                type="text"
                id="dni"
                name="dni"
                maxlength="8"
                inputmode="numeric"
                pattern="[0-9]{1,8}"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                title="Ingresá solo números y como máximo 8 dígitos">
        </div>

        <!-- Curso: lista desplegable con los cursos del usuario -->
        <div class="field">
            <label for="curso_id">Curso</label>
            <select id="curso_id" name="curso_id" required>
                <!-- Repetimos una <option> por cada curso que devolvió la base -->
                <?php while ($c = mysqli_fetch_assoc($cursos)) { ?>
                    <!-- "selected" deja marcada la opción del curso de donde venimos -->
                    <option value="<?php echo $c["id"]; ?>" <?php if ($c["id"] == $curso_elegido) { echo "selected"; } ?>>
                        <?php echo $c["nombre"]; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Correo del alumno: type="email" valida que tenga formato de correo -->
        <div class="field">
            <label for="correo_alumno">Correo del Alumno</label>
            <input type="email" id="correo_alumno" name="correo_alumno">
        </div>
    </div>

    <!-- Botones: Guardar envía el formulario; Cancelar vuelve a la lista de cursos -->
    <div class="btn-row">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline" href="cursos.php">Cancelar</a>
    </div>
</form>

<!-- Cierra la página (</main>, </body>, </html> y carga app.js) -->
<?php include "includes/footer.php"; ?>
