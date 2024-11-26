// Función para mostrar errores con SweetAlert y desplazar hacia el campo con error
function showError(input, message) {
    Swal.fire({
        icon: 'warning',
        title: 'Campo obligatorio',
        text: message,
        confirmButtonText: 'Aceptar'
    }).then(() => {
        setTimeout(() => {
            input.scrollIntoView({ behavior: "smooth", block: "center" });
            input.focus();
        }, 500); 
    });
}

let estadoFormulario = {
    domicilio: '',
    altura: 0,
    localidad: '',
    observaciones: '',
    obraSocial: 0,
    nombreObraSocial: '',
    numeroAfiliado: '',
    telefonos: []
};

function validarFormulario() {
    let esValido = true;

    const domicilioInput = document.getElementById('domicilio');
    const alturaInput = document.getElementById('altura');
    const localidadInput = document.getElementById('localidad');
    const obraSocialSi = document.getElementById('obraSi');
    const obraSocialNo = document.getElementById('obraNo');
    const nombreObraSocialInput = document.getElementById('nombreObraSocial');
    const numeroAfiliadoInput = document.getElementById('numeroAfiliado');
    const telefonos = document.getElementById('telefonosOculto').value;

    // Expresión regular para validar caracteres especiales
    const regexEspeciales = /^[a-zA-Z0-9\s,]+$/; // Permite letras, números, espacios y comas

    // Validar domicilio (obligatorio y sin caracteres especiales)
    if (domicilioInput.value.trim() === '') {
        esValido = false;
        showError(domicilioInput, 'Debe indicar su domicilio.');
        return esValido;
    } else if (!regexEspeciales.test(domicilioInput.value.trim())) {
        esValido = false;
        showError(domicilioInput, 'El domicilio no debe contener caracteres especiales.');
        return esValido;
    }

    // Validar altura (obligatorio, numérico, sin caracteres especiales)
    const alturaValue = alturaInput.value.trim();
    if (alturaValue === '') {
        esValido = false;
        showError(alturaInput, 'Debe indicar la altura.');
        return esValido;
    } else if (isNaN(alturaValue)) {
        esValido = false;
        showError(alturaInput, 'La altura debe ser un número.');
        return esValido;
    } else if (!/^\d+$/.test(alturaValue)) { // Solo números
        esValido = false;
        showError(alturaInput, 'La altura no debe contener caracteres especiales.');
        return esValido;
    }

    // Validar localidad (obligatorio y sin caracteres especiales)
    if (localidadInput.value.trim() === '') {
        esValido = false;
        showError(localidadInput, 'Debe indicar su localidad.');
        return esValido;
    } else if (!regexEspeciales.test(localidadInput.value.trim())) {
        esValido = false;
        showError(localidadInput, 'La localidad no debe contener caracteres especiales.');
        return esValido;
    }

    // Validar obra social (obligatorio)
    if (!obraSocialSi.checked && !obraSocialNo.checked) {
        esValido = false;
        showError(obraSocialSi, 'Debe indicar si tiene obra social.');
        return esValido;
    }

    if (obraSocialSi.checked) {
        if (nombreObraSocialInput.value.trim() === '') {
            esValido = false;
            showError(nombreObraSocialInput, 'Debe indicar el nombre de la obra social.');
            return esValido;
        }

        if (numeroAfiliadoInput.value.trim() === '') {
            esValido = false;
            showError(numeroAfiliadoInput, 'Debe indicar el número de afiliado.');
            return esValido;
        }
    }

    // Validar teléfonos (al menos uno requerido)
    if (!telefonos || telefonos.split(',').length === 0) {
        esValido = false;
        showError(telefonosOculto, 'Debe indicar al menos un teléfono.');
        return esValido;
    }

    return esValido;
}

function agregarTelefono() {
    const telefonoInput = document.getElementById('telefono');
    const telefono = telefonoInput.value.trim();

    if (telefono === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe ingresar un número de teléfono.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            setTimeout(() => {
                telefonoInput.scrollIntoView({ behavior: "smooth", block: "center" });
                telefonoInput.focus();
            }, 500); 
        });
        return;
    } else if (isNaN(telefono)) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'El teléfono debe contener solamente números.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            setTimeout(() => {
                telefonoInput.scrollIntoView({ behavior: "smooth", block: "center" });
                telefonoInput.focus();
            }, 500); 
        });
        return;
    } else if (telefono.length < 10 || telefono.length > 20) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'El teléfono debe tener entre 10 y 20 caracteres.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            setTimeout(() => {
                telefonoInput.scrollIntoView({ behavior: "smooth", block: "center" });
                telefonoInput.focus();
            }, 500); 
        });
        return;
    } else if (estadoFormulario.telefonos.includes(telefono)) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Este número de teléfono ya está agregado.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            setTimeout(() => {
                telefonoInput.scrollIntoView({ behavior: "smooth", block: "center" });
                telefonoInput.focus();
            }, 500); 
        });
        return;
    }

    estadoFormulario.telefonos.push(telefono);
    actualizarTelefonosOcultos();

    telefonoInput.value = '';
}

function actualizarTelefonosOcultos() {
    const telefonosOculto = document.getElementById('telefonosOculto');
    const listaTelefonosDiv = document.getElementById('listaTelefonos');

    // Unir los teléfonos en una cadena separada por comas
    telefonosOculto.value = estadoFormulario.telefonos.join(',');

    // Mostrar los teléfonos en una lista con un botón para eliminar
    listaTelefonosDiv.innerHTML = estadoFormulario.telefonos.map((telefono, index) => `
        <br>
        <div class="telefono-container">
            <label class="telefono-label item" style="padding: 10px 20px;">${telefono}</label>
            <button type="button" class="eliminar" onclick="eliminarTelefono(${index})">Eliminar</button>
        </div>
    `).join('');
}

function eliminarTelefono(index) {
    // Eliminar el teléfono de la lista
    estadoFormulario.telefonos.splice(index, 1);
    actualizarTelefonosOcultos();
}

function cargarTelefonosIniciales() {
    const telefonosOculto = document.getElementById('telefonosOculto').value;
    // Convertir la cadena de teléfonos a un array
    estadoFormulario.telefonos = telefonosOculto.split(',')
    .map(telefono => telefono.trim())
    .filter(Boolean); // Filtrar valores vacíos
    
    actualizarTelefonosOcultos();
}
cargarTelefonosIniciales();


// Función para obtener y actualizar los datos del formulario
function obtenerDatosFormulario() {

    estadoFormulario.domicilio = document.getElementById('domicilio').value;

    estadoFormulario.altura = document.getElementById('altura').value;

    estadoFormulario.localidad = document.getElementById('localidad').value;

    estadoFormulario.observaciones = document.getElementById('observaciones').value;

    // Actualizar obra social, si "No" está seleccionado, el valor queda en blanco
    const obraSocialSi = document.getElementById('obraSi').checked;
    estadoFormulario.obraSocial = obraSocialSi ? 1 : 0;

    // Solo almacenar los datos de obra social si está seleccionada la opción "Sí"
    if (obraSocialSi) {
        estadoFormulario.nombreObraSocial = document.getElementById('nombreObraSocial').value;
        estadoFormulario.numeroAfiliado = document.getElementById('numeroAfiliado').value;
    } else {
        estadoFormulario.nombreObraSocial = '';
        estadoFormulario.numeroAfiliado = '';
    }

    enviarDatos(estadoFormulario); // Llama a la función para enviar los datos
}

async function enviarDatos(datos) {
    const response = await fetch('../../php/insertAnexoVII.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });
    
    const result = await response.json();

    if (response.ok) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: result.message,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back(); // Volver a la página anterior
            }
        });
    } else {
        console.error('Error en la solicitud:', result.message);
    }
}


// Llamada a la función cuando se hace clic en un botón
document.getElementById('cargarAnexoVII').addEventListener('click', function(event) {
    event.preventDefault(); // Evitar el envío por defecto del formulario
    if (validarFormulario()) {
        obtenerDatosFormulario(); // Obtiene los datos actualizados y los envía
    }
});

// Agregar evento para el botón de agregar teléfono
document.getElementById('agregarTelefono').addEventListener('click', agregarTelefono);

// Mostrar u ocultar los inputs de obra social
document.querySelectorAll('input[name="obraSocial"]').forEach((element) => {
    element.addEventListener('change', function() {
        const obraSocialNombreDiv = document.getElementById('obraSocialNombreDiv');
        const obraSocialNumeroDiv = document.getElementById('obraSocialNumeroDiv');

        if (this.value === 'si') {
            obraSocialNombreDiv.style.display = 'block'; // Mostrar campos de nombre y número de obra social
            obraSocialNumeroDiv.style.display = 'block';
        } else {
            obraSocialNombreDiv.style.display = 'none'; // Ocultar campos de nombre y número de obra social
            obraSocialNumeroDiv.style.display = 'none';
        }
    });
});
