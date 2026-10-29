<?php
// Conecta con la base de datos (crea la variable $conexion) y trae las funciones comunes (escapar, mostrar_error)
include "includes/conexion.php";
// Si no hay sesión iniciada manda al login; si la hay, crea $usuario_id con el número del docente
include "includes/verificar_sesion.php";

// (int) convierte el valor a número: así nadie puede meter texto raro en la URL
$id = (int) $_GET["id"];

// Buscamos el curso
$sql = "SELECT * FROM cursos WHERE id = $id";
$curso = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// Alumnos del curso
// SELECT * trae todas las columnas; ORDER BY nombre los ordena alfabéticamente
$sql = "SELECT * FROM alumnos WHERE curso_id = $id ORDER BY nombre";
$alumnos = mysqli_query($conexion, $sql);
// Cuenta cuántos alumnos tiene el curso (se usa varias veces más abajo)
$cantidad = mysqli_num_rows($alumnos);

// Datos que usa header.php: $titulo es el texto de la pestaña (acá, el nombre del curso)
// y $activo marca la opción seleccionada del menú
$titulo = $curso["nombre"];
$activo = "cursos";
// Dibuja la parte de arriba de la página (la estructura y el menú lateral)
include "includes/header.php";
?>

<!-- Encabezado: nombre del curso, materia, cantidad de alumnos y botón para agregar un alumno -->
<div class="page-head">
    <div>
        <h2><?php echo $curso["nombre"]; ?></h2>
        <small><?php echo $curso["materia"]; ?> · <?php echo $cantidad; ?> alumnos</small>
    </div>
    <!-- ?curso=número deja el curso ya elegido en el formulario de alumno nuevo -->
    <a class="btn btn-primary" href="alumno_nuevo.php?curso=<?php echo $id; ?>">+ Alumno</a>
</div>

<!-- Sin alumnos: mostramos un aviso. Con alumnos: el buscador y la lista -->
<?php if ($cantidad == 0) { ?>
    <div class="empty">Este curso todavía no tiene alumnos.</div>
<?php } else { ?>

    <!-- oninput llama a la función buscar() de app.js -->
    <input class="search" type="search" id="buscador" oninput="buscar()" placeholder="Buscar alumno...">

    <!-- Una tarjeta por alumno, en dos columnas -->
    <div class="grid-2">
        <!-- while repite el bloque por cada alumno del curso -->
        <?php while ($alumno = mysqli_fetch_assoc($alumnos)) { ?>
            <!-- La tarjeta es un enlace al perfil del alumno. data-nombre guarda su nombre
                 para que el buscador (la función buscar() de app.js) pueda mostrarla u ocultarla -->
            <a class="card tarjeta-alumno" data-nombre="<?php echo $alumno["nombre"]; ?>"
               href="alumno.php?id=<?php echo $alumno["id"]; ?>">
                <h3><?php echo $alumno["nombre"]; ?></h3>
                <!-- El DNI se muestra debajo del nombre -->
                <small>DNI <?php echo $alumno["dni"]; ?></small>
            </a>
        <?php } ?>
    </div>

<?php } ?>

<!-- Botones de abajo: tomar asistencia (solo si hay alumnos) y eliminar el curso -->
<div class="btn-row">
    <?php if ($cantidad > 0) { ?>
        <a class="btn btn-primary" href="asistencia.php?id=<?php echo $id; ?>">Tomar asistencia</a>
    <?php } ?>

    <!-- confirm() muestra un cuadro "Aceptar / Cancelar" antes de enviar el formulario -->
    <form action="eliminar_curso.php" method="post"
          onsubmit="return confirm('¿Seguro que querés eliminar este curso con todos sus alumnos?');">
        <!-- Campo oculto: envía el número del curso a eliminar_curso.php sin que se vea en pantalla -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button class="btn btn-outline">Eliminar curso</button>
    </form>
</div>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>
