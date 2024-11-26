document.addEventListener("DOMContentLoaded", function() {
    // Llama a la función cuando se carga la página / redimensiona la ventana
    window.onload = ajustarTextoLabel;
    window.onresize = ajustarTextoLabel;

    document.querySelectorAll('input[name="distanciaSalida"]').forEach(function (radio) {
        radio.addEventListener('change', function() {
            validarDistanciaSalida();
        });
    });

    const limite = document.getElementById('fechaLimite');
    limite.addEventListener('keydown', function (event) {
        event.preventDefault();
    }); 

    function ajustarTextoLabel() {    
        const width = window.innerWidth;
        const labels = document.getElementsByClassName('text');  // Devuelve una colección
        const selectRadio = document.getElementsByClassName('secundario');  // Devuelve una colección
        // Cambiar el texto según el ancho de la ventana
        if (width < 920) {
            selectRadio[2].textContent = "Rep. Inst.";
            labels[0].textContent = "Dentro del barrio.";
            labels[1].textContent = "Distrito (1)";
            labels[2].textContent = "Distrito (2)";
            labels[3].textContent = "Fuera Provincia";
            labels[4].textContent = "Fuera del País";
        } else {
            labels[0].textContent = "Dentro del barrio o área geográfica inmediata, con regreso en el día";
            labels[1].textContent = "Dentro del Distrito o fuera de él, con regreso en el día.";
            labels[2].textContent = "Dentro del Distrito o fuera de él, con más de 24 horas de duración.";
            labels[3].textContent = "Fuera de la Provincia de Buenos Aires.";
            labels[4].textContent = "Fuera del País.";
        }
    }

    function validarDistanciaSalida() {
        const width = window.innerWidth;
        const distanciaSeleccionada = document.querySelector('input[name="distanciaSalida"]:checked').value;
    
        let alertMessage;
    
        if (width < 920) {
            switch(distanciaSeleccionada) {
                case "1":
                    alertMessage = 'Salida dentro del barrio o área geográfica inmediata, con regreso en el día. (5 días previos de entrega)';
                    break;
                case "2":
                    alertMessage = 'Salida dentro del Distrito o fuera de él, con regreso en el día. (10 días previos de entrega)';
                    break;
                case "3":
                    alertMessage = 'Salida dentro del Distrito o fuera de él, con más de 24 horas de duración. (15 días previos de entrega)';
                    break;
                case "4":
                    alertMessage = 'Salida fuera de la Provincia de Buenos Aires. (30 días previos de entrega)';
                    break;
                case "5":
                    alertMessage = 'Salida fuera del país. (30 días previos de entrega)';
                    break;
                default:
                    alertMessage = 'Opción no válida';
                    return; // Detener la ejecución si no hay opción válida
            }
            Swal.fire({
                icon: 'info', // Cambiar a 'info' para mostrar un icono informativo
                title: 'Descripción',
                text: alertMessage,
                confirmButtonText: 'OK' // Texto del botón de confirmación
            });
        } else {
            switch(distanciaSeleccionada) {
                case "1":
                    alertMessage = 'Esta salida necesita 5 días previos de entrega.';
                    break;
                case "2":
                    alertMessage = 'Esta salida necesita 10 días previos de entrega.';
                    break;
                case "3":
                    alertMessage = 'Esta salida necesita 15 días previos de entrega.';
                    break;
                case "4":
                    alertMessage = 'Esta salida necesita 30 días previos de entrega.';
                    break;
                case "5":
                    alertMessage = 'Esta salida necesita 30 días previos de entrega.';
                    break;
                default:
                    alertMessage = 'Opción no válida';
                    return; // Detener la ejecución si no hay opción válida
            }
            Swal.fire({
                icon: 'info', // Cambiar a 'info' para mostrar un icono informativo
                title: 'Atención',
                text: alertMessage,
                confirmButtonText: 'OK' // Texto del botón de confirmación
            });
        }
    
        document.getElementById("fechaSalida").disabled = false;
        document.getElementById("horaSalida").disabled = false;
        document.getElementById("fechaRegreso").disabled = false;
        document.getElementById("horaRegreso").disabled = false;
    
        if (["1", "2"].includes(distanciaSeleccionada)) {
            ocultarInputs();
        } else {
            mostrarInputs();
        }
    }    
    
    document.getElementById("fechaSalida").addEventListener("blur", function(){
        calcularTiempoLimite();
        validarFechas();
    });
    
    document.getElementById("horaSalida").addEventListener("blur", function(){
        calcularTiempoLimite();
        validarFechas();
    });

    document.getElementById("fechaRegreso").addEventListener("blur", function(){
        calcularTiempoLimite();
        validarFechas();
    });

    document.getElementById("horaRegreso").addEventListener("blur", function(){
        calcularTiempoLimite();
        validarFechas();
    });

    let fechasValidas = [];
    
    function calcularTiempoLimite() {
        const fechaSalidaInput = document.getElementById('fechaSalida').value;
        const distanciaSeleccionada = document.querySelector('input[name="distanciaSalida"]:checked').value;
        let diasARestar = 0;
    
        // Asignación de los días a restar según la distancia seleccionada
        if (distanciaSeleccionada == 1) {
            diasARestar = 5;
        } else if (distanciaSeleccionada == 2) {
            diasARestar = 10;
        } else if (distanciaSeleccionada == 3) {
            diasARestar = 15;
        } else if (distanciaSeleccionada == 4 || distanciaSeleccionada == 5) {
            diasARestar = 30;
        }
    
        // Validar que se haya seleccionado una fecha de salida
        if (!fechaSalidaInput) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor ingrese una fecha de salida. ATENCIÓN: Las fechas se borrarán.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                limpiarCampos();
            });
            return;
        }
    
        // Parsear la fecha de salida desde el input (en formato YYYY-MM-DD)
        const fechaSalida = dateFns.parseISO(fechaSalidaInput);
        const fechaLimite = dateFns.subDays(fechaSalida, diasARestar); // Fecha límite
    
        const feriados = [
            "01-01", "24-03", "02-04", "01-05", 
            "25-05", "20-06", "09-07", "08-12", "25-12"
        ];
    
        // Función para verificar si una fecha es feriado
        function esFeriado(fecha) {
            const diaMes = dateFns.format(fecha, 'dd-MM');
            return feriados.includes(diaMes);
        }
    
        fechasValidas = [];
        let fecha = fechaSalida;
    
        // Mientras necesitemos más fechas válidas y no hayamos alcanzado la fecha límite
        while (fechasValidas.length < diasARestar && fecha >= fechaLimite) {
            // Verificar que la fecha no sea fin de semana ni feriado
            if (!dateFns.isWeekend(fecha) && !esFeriado(fecha)) {
                fechasValidas.push(dateFns.format(fecha, 'yyyy-MM-dd'));
            } else {
                console.log(`Fecha excluida: ${dateFns.format(fecha, 'yyyy-MM-dd')}`);
            }
            // Retroceder un día
            fecha = dateFns.subDays(fecha, 1);
        }   
    
        // Si no hay suficientes fechas válidas, seguir retrocediendo
        while (fechasValidas.length < diasARestar) {
            fecha = dateFns.subDays(fecha, 1);
            if (!dateFns.isWeekend(fecha) && !esFeriado(fecha)) {
                fechasValidas.push(dateFns.format(fecha, 'yyyy-MM-dd'));
            }
        }

        const primeraFechaValida = fechasValidas[fechasValidas.length - 1];
        const fechaPrimeraValida = dateFns.parseISO(primeraFechaValida);

        if (fechaSalida < new Date()) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha de salida pasada',
                text: 'Ha ingresado una fecha de salida pasada. Por favor, seleccione una fecha de salida futura. ATENCION: Las fechas se borraran.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                limpiarCampos();
            });
            return;
        } else if (fechaPrimeraValida < new Date()) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha de salida muy cercana',
                text: `La fecha de salida no cumple con la Resolución Provincial. Por favor, seleccione una fecha de salida que posea el mínimo de días de entrega requeridos (${diasARestar} DÍAS). ATENCION: Las fechas se borrarán.`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                limpiarCampos();
            });            
            return;
        }

        const fechaLimiteInput = primeraFechaValida + "T12:00"; 
        document.getElementById("fechaLimite").value = fechaLimiteInput;
        // console.log('Fechas válidas:', fechasValidas);
        // console.log(fechaLimiteInput);
    }

    function validarFechas() {
        const fechaSalida = document.getElementById("fechaSalida").value;
        const horaSalida = document.getElementById("horaSalida").value;
        const fechaRegreso = document.getElementById("fechaRegreso").value;
        const horaRegreso = document.getElementById("horaRegreso").value;
    
        if (!fechaSalida || !horaSalida || !fechaRegreso || !horaRegreso) return true;
    
        const fechaHoraActual = new Date();
        const fechaHoraSalida = new Date(`${fechaSalida}T${horaSalida}`);
        const fechaHoraRegreso = new Date(`${fechaRegreso}T${horaRegreso}`);
    
        // Calcular la diferencia en horas
        const diferenciaMs = fechaHoraRegreso - fechaHoraSalida;
        const diferenciaHoras = diferenciaMs / (1000 * 60 * 60); 
    
        const distanciaSeleccionada = document.querySelector('input[name="distanciaSalida"]:checked')?.value;

        const opcionesMenos24Horas = ["1", "2"];
    
        // Validaciones de tiempo de salida y regreso
        if (opcionesMenos24Horas.includes(distanciaSeleccionada)) {
            if (diferenciaHoras >= 24) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duración Inválida',
                    text: 'Has seleccionado una salida de menos de 24 horas, pero la diferencia es mayor a 24 horas. ATENCION: Las fechas se borraran, ingrese fechas válidas.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    limpiarCampos();
                });
                return false;
            }
        } else {
            if (diferenciaHoras < 24) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duración Inválida',
                    text: 'Has seleccionado una salida de más de 24 horas, pero la diferencia es menor a 24 horas. ATENCION: Las fechas se borraran, ingrese fechas válidas.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    limpiarCampos();
                });
                return false;
            }
        }

        const unAnoEnMilisegundos = 365 * 24 * 60 * 60 * 1000;
        if (fechaHoraSalida - fechaHoraActual > unAnoEnMilisegundos || 
            fechaHoraSalida.getFullYear() < 2024 || fechaHoraSalida.getFullYear() > 2100) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha Inválida',
                text: 'La fecha de salida no puede ser más de un año en el futuro. ATENCION: Las fechas se borraran, ingrese fechas válidas.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                limpiarCampos();
            });
            return false; // Salida inválida
        }
    
        // Validación de fecha y hora de regreso
        if (isNaN(fechaHoraRegreso.getTime()) || fechaHoraRegreso <= fechaHoraActual) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha Inválida',
                text: 'La fecha y hora de regreso no pueden ser en el pasado. ATENCION: Las fechas se borraran, ingrese fechas válidas.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                limpiarCampos();
            });
            return false; // Regreso inválido
        }
    
        if (fechaHoraRegreso <= fechaHoraSalida) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha Inválida',
                text: 'La fecha y hora de regreso deben ser posteriores a la fecha y hora de salida. ATENCION: Las fechas se borraran, ingrese fechas válidas.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                limpiarCampos();
            });
            return false; // Regreso no posterior a salida
        }
    
        return true; // Todo está bien
    }    
    
    function limpiarCampos() {
        document.getElementById("fechaSalida").value = "";
        document.getElementById("horaSalida").value = "";
        document.getElementById("fechaRegreso").value = "";
        document.getElementById("horaRegreso").value = "";
        document.getElementById("fechaLimite").value = "";
    }

    function ocultarInputs() {
        var elementos = [
            document.getElementById("nombreHospedaje"),
            document.getElementById("domicilioHospedaje"),
            document.getElementById("telefonoHospedaje"),
            document.getElementById("localidadHospedaje"),
            document.getElementById("nH"),
            document.getElementById("dH"),
            document.getElementById("tH"),
            document.getElementById("lH")
        ];

        elementos.forEach(function(elemento) {
            if (elemento) {
                elemento.style.display = "none";
                elemento.disabled = true;
            }
        });
    }
    
    function mostrarInputs() {
        var elementos = [
            document.getElementById("nombreHospedaje"),
            document.getElementById("domicilioHospedaje"),
            document.getElementById("telefonoHospedaje"),
            document.getElementById("localidadHospedaje"),
            document.getElementById("nH"),
            document.getElementById("dH"),
            document.getElementById("tH"),
            document.getElementById("lH")
        ];

        elementos.forEach(function(elemento) {
            if (elemento) {
                elemento.style.display = "";
                elemento.disabled = false;
            }
        });
    }

    function validarAnexoIV(event) {
        event.preventDefault();

        const anexo9 = document.querySelector('input[name="anexoVIII"]:checked');
        if (!anexo9) {
            const tipoSalidaContainer = document.querySelector('input[name="anexoVIII"]').closest('div');
            Swal.fire({
                icon: 'warning',
                title: 'Campo no seleccionado',
                text: 'Por favor indique si va a utilizar el Anexo VIII.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    tipoSalidaContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.querySelector('input[name="anexoVIII"]').focus();
                }, 300);
            });
            return;
        }

        const tipoSalida = document.querySelector('input[name="tipoSalida"]:checked');
        if (!tipoSalida) {
            const tipoSalidaContainer = document.querySelector('input[name="tipoSalida"]').closest('div');
            Swal.fire({
                icon: 'warning',
                title: 'Campo no seleccionado',
                text: 'Por favor selecciona una opción para el tipo de salida.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    tipoSalidaContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.querySelector('input[name="tipoSalida"]').focus();
                }, 300);
            });
            return;
        }

        const distanciaSalida = document.querySelector('input[name="distanciaSalida"]:checked');
        if (!distanciaSalida) {
            const distSalidaContainer = document.querySelector('input[name="distanciaSalida"]').closest('div');
            Swal.fire({
                icon: 'warning',
                title: 'Campo no seleccionado',
                text: 'Por favor selecciona una opción para la distancia de la salida.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    distSalidaContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.querySelector('input[name="distanciaSalida"]').focus();
                }, 300);
            });
            return;
        }

        const esValido = validarFechas();
        if (!esValido) return;

        const fields = [
            'denominacionProyecto',
            'lugarVisita',
            'direccionVisita',
            'localidadVisita',
            'regionVisita',
            'fechaSalida',
            'lugarSalida',
            'horaSalida',
            'fechaRegreso',
            'lugarRegreso',
            'horaRegreso',
            'itinerario',
            'actividades',
            'objetivosSalida',
            'cronograma',
            'gastosEstimativos'
        ];

        let firstInvalidField = null;

        // Validación de campos vacíos obligatorios
        for (let field of fields) {
            const element = document.getElementById(field);
            if (element && element.value.trim() === '') {
                firstInvalidField = element;
                break;
            }
        }

        // Validación de campos de hospedaje solo si están visibles
        if (document.getElementById("nombreHospedaje").style.display !== "none") {
            const hospedajeFields = [
                'nombreHospedaje',
                'domicilioHospedaje',
                'telefonoHospedaje',
                'localidadHospedaje'
            ];

            for (let field of hospedajeFields) {
                const element = document.getElementById(field);
                if (element && element.value.trim() === '') {
                    firstInvalidField = element;
                    break;
                }
            }
        }

        if (firstInvalidField) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos Incompletos',
                text: `El campo "${firstInvalidField.previousElementSibling.textContent}" es obligatorio.`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }, 300);
            });
            return;
        }

        // Validación del nombre encargado
        var nombreEncargado = document.getElementById("nombreEncargado").value;
        var nombrePattern = /^[A-Za-z\s]+$/;
        if (!nombrePattern.test(nombreEncargado)) {
            Swal.fire({
                icon: 'warning',
                title: 'Nombre Inválido',
                text: "El nombre del encargado no debe contener números.",
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    document.getElementById("nombreEncargado").scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.getElementById("nombreEncargado").focus();
                }, 300);
            });
            return;
        }

        // Validación de telefonoHospedaje
        var telefonoHospedaje = document.getElementById("telefonoHospedaje");
        var telefonoValue = telefonoHospedaje.value.trim();
        var allowDashes = telefonoHospedaje.dataset.allowDashes === "true";
        const regex = allowDashes ? /^[\d-]{10,13}$/ : /^\d{10,13}$/;
        
        if (telefonoValue && !regex.test(telefonoValue) && telefonoHospedaje.disabled == false) {
            Swal.fire({
                icon: 'warning',
                title: 'Número de Teléfono Inválido',
                text: `El campo "${document.querySelector(`label[for=${telefonoHospedaje.id}]`).textContent}" debe contener solo números, con un máximo de 13 caracteres.`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    telefonoHospedaje.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    telefonoHospedaje.focus();
                }, 300);
            });
            event.preventDefault();
            return;
        }        

        const regionVisita = document.getElementById('regionVisita');
        if (!/^\d+$/.test(regionVisita.value.trim())) {
            Swal.fire({
                icon: 'warning',
                title: 'Región Inválida',
                text: 'El campo Región de Visita solo debe contener números.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    regionVisita.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    regionVisita.focus();
                }, 300);
            });
            return;
        }

        const checkboxes = document.querySelectorAll(".form-check-input");
        checkboxes.forEach(function(checkbox) {
            checkbox.value = checkbox.checked ? "1" : "0";
        });

        enviarFormulario('formAnexoIV', '../../php/insertAnexoIV.php', 'Anexo 4 cargado correctamente!');
    }

    function enviarFormulario(formId, actionUrl, successMessage) {
        var form = document.getElementById(formId);
        if (!form) return;
    
        var formData = new FormData(form);
        var fechaAlert = document.getElementById("fechaLimite").value;
        var fechaLimite = new Date(fechaAlert);
        var diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        var nombreDia = diasSemana[fechaLimite.getDay()];
        var numeroDia = fechaLimite.getDate();
        var nombreMes = meses[fechaLimite.getMonth()];
        var anio = fechaLimite.getFullYear();
        console.log(`Tiene hasta el ${nombreDia} ${numeroDia} de ${nombreMes} de ${anio} a las 12:00 del mediodía para entregar el proyecto a dirección.`)
        fetch(actionUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Response from server:", data);  // Log respuesta
            if (data.trim() === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: successMessage,
                    text: `Tiene hasta el ${nombreDia} ${numeroDia} de ${nombreMes} de ${anio} para entregar el proyecto a dirección.`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.replace('../../indexs/profesores/menuAdministrarSalidas.php');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar el anexo',
                    text: data,
                    confirmButtonText: 'Intentar de nuevo'
                });
            }
        }).catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error en la conexión',
                text: 'No se pudo conectar al servidor. Por favor intenta de nuevo.',
                confirmButtonText: 'Ok'
            });
        });
    }

    document.getElementById('cargarAnexoIV').addEventListener('click', validarAnexoIV);
});