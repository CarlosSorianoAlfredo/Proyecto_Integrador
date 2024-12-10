function validacionNumero2(e) {
    // Mensaje de prueba para verificar si la función se ejecuta
    console.log("Validando entrada...");

    // Detecta el código de la tecla presionada
    let code = e.which || e.keyCode;

    // Permitir solo números (48-57: códigos ASCII de 0-9)
    if (code > 31 && (code < 48 || code > 57)) {
        // Bloquea cualquier otro carácter
        e.preventDefault();
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Vincular validación al campo específico
    const campoNumero = document.getElementById("caja_num_control");

    if (campoNumero) {
        // Agregar el evento keypress para bloquear caracteres no válidos
        campoNumero.addEventListener("keypress", validacionNumero2);
    } else {
        console.warn("El campo con ID 'caja_num_control' no se encontró.");
    }
});
function validarSoloLetras(e) {
    const charCode = e.which || e.keyCode;
    const validChar = 
        charCode === 32 || // Espacio
        (charCode >= 65 && charCode <= 90) || // Letras mayúsculas (A-Z)
        (charCode >= 97 && charCode <= 122); // Letras minúsculas (a-z)

    if (!validChar) {
        e.preventDefault();
        console.log(`Carácter bloqueado: ${charCode}`);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const fields = ["caja_nombre", "inputPrimerAp", "inputSegundoAp"].map(id => document.getElementById(id));

    fields.forEach(field => {
        if (field) {
            field.addEventListener("keydown", validarSoloLetras);
        }
    });
});
