// =====================================================
// app.js: funciones de JavaScript que usan varias páginas
// (se carga al final de cada página desde includes/footer.php)
// =====================================================

// ===== Buscador de alumnos =====
// Se llama desde el buscador de curso_detalle.php con oninput="buscar()".
// "oninput" ejecuta la función cada vez que la persona escribe o borra una letra.
function buscar() {
    // Leemos lo que escribió la persona en el buscador y lo pasamos a minúsculas,
    // así "ana" también encuentra a "Ana" (no importan las mayúsculas)
    var texto = document.getElementById("buscador").value.toLowerCase();

    // Juntamos todas las tarjetas de alumnos de la página (todas tienen la clase "tarjeta-alumno")
    var tarjetas = document.querySelectorAll(".tarjeta-alumno");

    // Recorremos las tarjetas una por una: i empieza en 0 y llega hasta la última
    for (var i = 0; i < tarjetas.length; i++) {
        // Cada tarjeta guarda el nombre del alumno en su atributo data-nombre;
        // también lo pasamos a minúsculas para poder compararlo con lo escrito
        var nombre = tarjetas[i].getAttribute("data-nombre").toLowerCase();

        // includes() responde true si el nombre contiene el texto buscado
        if (nombre.includes(texto)) {
            tarjetas[i].style.display = "";      // se muestra (vuelve a su estilo normal)
        } else {
            tarjetas[i].style.display = "none";  // se oculta
        }
    }
}

// ===== Campo nombre: solo permite letras y espacios =====
// Se llama desde los formularios con oninput="soloLetras(this)".
// Bloquea cualquier número o símbolo que intente escribirse.
function soloLetras(campo) {
    campo.value = campo.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
}

// ===== Campo DNI: solo permite números =====
// Se llama desde los formularios de alumno con oninput="soloNumeros(this)".
// "this" es el propio campo en el que se está escribiendo.
// Funciona al tipear y también al pegar texto.
function soloNumeros(campo) {
    // /[^0-9]/g es una expresión regular:
    //   [0-9]  = un dígito del 0 al 9
    //   ^      = dentro de los corchetes significa "todo lo que NO sea"
    //   g      = "global": reemplaza todos los que encuentre, no solo el primero
    // O sea: encuentra letras, espacios, puntos, guiones, etc. y replace() los cambia por "" (los borra).
    campo.value = campo.value.replace(/[^0-9]/g, "");
}
