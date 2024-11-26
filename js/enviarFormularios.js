document.addEventListener("DOMContentLoaded", function() {
    var anexoVIIIDiv = document.getElementById('anexoVIII');
    var anexoVIIIDivTab = document.getElementById('anexoVIII-tab');
    
    var planillaDiv = document.getElementById('planillaInfo');
    var planillaDivTab = document.getElementById('planillaInfo-tab');
    
    if (anexoVIIIHabil === "2") {
        if (anexoVIIIDiv) anexoVIIIDiv.style.display = 'none';
        if (anexoVIIIDivTab) anexoVIIIDivTab.style.display = 'none';
    }

    if (planillaHabil === "2") {
        if (planillaDiv) planillaDiv.style.display = 'none';
        if (planillaDivTab) planillaDivTab.style.display = 'none';
    }

    $.ajax({
        method: 'GET',
        url: '../../php/listadoAnexoVIII.php',
        success: function(response) {
            // Log the raw response to inspect
            console.log(response);
    
            // Check if the response is a valid JSON string
            try {
                const respuesta = JSON.parse(response);
                console.log(respuesta);
    
                if (respuesta.status === "registrosSI") {
                    generarDatosCargados(respuesta.data); // Process the data
                } 
                else if (respuesta.status === "registrosNO") {
                    const selectVehiculos = document.getElementById("cantidadVehiculos"); 
                    selectVehiculos.addEventListener("change", generarVehiculos);
    
                    const selectConductores = document.getElementById("cantidadConductores"); 
                    selectConductores.addEventListener("change", generarConductores);
                }
            } catch (e) {
                // Handle case where response is not valid JSON
                console.error("Response is not valid JSON", e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la respuesta del servidor. No se recibió JSON.',
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al obtener los datos.',
            });
        }
    });    

    function capturarDatos() {
        let numeroRegistroArray = [];
        let fechaHabilitacionArray = [];
        let tipoHabilitacionArray = [];
        let cantidadAsientosArray = [];
        let vigenciaVTVArray = [];
        let polizaArray = [];
        let tipoSeguroArray = [];
    
        let nombresConductoresArray = [];
        let dnisConductoresArray = [];
        let carnetConductoresArray = [];
        let vigenciaConductoresArray = [];
    
        // Verificar existencia antes de acceder a los valores
        const cantidadConductoresElement = document.getElementById("cantidadConductores");
        const cantidadVehiculosElement = document.getElementById("cantidadVehiculos");
    
        const cantidadConductores = cantidadConductoresElement ? cantidadConductoresElement.value : 0;
        const cantidadVehiculos = cantidadVehiculosElement ? cantidadVehiculosElement.value : 0;
    
        for (let i = 0; i < cantidadVehiculos; i++) {
            numeroRegistroArray.push(document.getElementById(`nroRegistro${i}`)?.value || '');
            fechaHabilitacionArray.push(document.getElementById(`fechaHabilitacion${i}`)?.value || '');
            tipoHabilitacionArray.push(document.getElementById(`tipoHabilitacion${i}`)?.value || '');
            cantidadAsientosArray.push(document.getElementById(`cantAsientos${i}`)?.value || '');
            vigenciaVTVArray.push(document.getElementById(`vigenciaVTV${i}`)?.value || '');
            polizaArray.push(document.getElementById(`nroPoliza${i}`)?.value || '');
            tipoSeguroArray.push(document.getElementById(`tipoSeguro${i}`)?.value || '');
    
            // console.log(`Vehículo: ${i + 1}`, {
            //     numeroRegistro: numeroRegistroArray[i],
            //     fechaHabilitacion: fechaHabilitacionArray[i],
            //     tipoHabilitacion: tipoHabilitacionArray[i],
            //     cantidadAsientos: cantidadAsientosArray[i],
            //     vigenciaVTV: vigenciaVTVArray[i],
            //     poliza: polizaArray[i],
            //     tipoSeguro: tipoSeguroArray[i]
            // });
        }
    
        for (let i = 0; i < cantidadConductores; i++) {
            nombresConductoresArray.push(document.getElementById(`nombreConductor${i}`)?.value || '');
            dnisConductoresArray.push(document.getElementById(`dniConductor${i}`)?.value || '');
            carnetConductoresArray.push(document.getElementById(`carnetConducir${i}`)?.value || '');
            vigenciaConductoresArray.push(document.getElementById(`vigenciaConductor${i}`)?.value || '');
    
            // Mostrar cada dato capturado en la consola
            // console.log(`Conductor: ${i + 1}`, {
            //     nombreConductor: nombresConductoresArray[i],
            //     dniConductor: dnisConductoresArray[i],
            //     carnetConducir: carnetConductoresArray[i],
            //     vigenciaConductor: vigenciaConductoresArray[i]
            // });
        }
    
        document.getElementById("numeroRegistroArray")?.setAttribute("value", numeroRegistroArray.join("%"));
        document.getElementById("fechaHabilitacionArray")?.setAttribute("value", fechaHabilitacionArray.join("%"));
        document.getElementById("tipoHabilitacionArray")?.setAttribute("value", tipoHabilitacionArray.join("%"));
        document.getElementById("cantidadAsientosArray")?.setAttribute("value", cantidadAsientosArray.join("%"));
        document.getElementById("vigenciaVTVArray")?.setAttribute("value", vigenciaVTVArray.join("%"));
        document.getElementById("polizaArray")?.setAttribute("value", polizaArray.join("%"));
        document.getElementById("tipoSeguroArray")?.setAttribute("value", tipoSeguroArray.join("%"));
    
        document.getElementById("nombresConductoresArray")?.setAttribute("value", nombresConductoresArray.join("%"));
        document.getElementById("dnisConductoresArray")?.setAttribute("value", dnisConductoresArray.join("%"));
        document.getElementById("carnetConductoresArray")?.setAttribute("value", carnetConductoresArray.join("%"));
        document.getElementById("vigenciaConductoresArray")?.setAttribute("value", vigenciaConductoresArray.join("%"));
    }
    
    function validateAndSubmitAnexoVIII(event) {
        event.preventDefault();
    
        const fields = [
            'nombreEmpresa',
            'nombreGerente',
            'domicilioEmpresa',
            'telefonoEmpresa',
            'domicilioGerente',
            'telefono',
            'telefonoMovil',
            'titularidadVehiculo',
            'aseguradora',
        ];
    
        const vehiculosValidar = [
            'nroRegistro',
            'fechaHabilitacion',
            'tipoHabilitacion',
            'cantAsientos',
            'vigenciaVTV',
            'nroPoliza',
            'tipoSeguro'
        ];
    
        const conductoresValidar = [
            'nombreConductor',
            'dniConductor',
            'carnetConducir',
            'vigenciaConductor'
        ];
    
        let firstInvalidField = null;
    
        function parseDate(dateStr) {
            const [day, month, year] = dateStr.split('/');
            return new Date(`${year}-${month}-${day}`);
        }
    
        function isValidDate(dateStr) {
            const date = parseDate(dateStr);
            const today = new Date();
    
            today.setHours(0, 0, 0, 0);
    
            return date > today;
        }
    
        function containsSpecialCharacters(str) {
            const regex = /[^a-zA-Z0-9\s]/g;
            return regex.test(str);
        }
    
        function containsOnlyNumbers(str) {
            return /^[0-9]+$/.test(str);
        }
    
        function containsOnlyLetters(str) {
            return /^[a-zA-Z\s]+$/.test(str);
        }
    
        function containsOnlyLettersAndNumbers(str) {
            return /^[a-zA-Z0-9\s]+$/.test(str);
        }
    
        function isValidPhoneNumber(number) {
            return /^[0-9]{10,15}$/.test(number);
        }
    
        for (let field of fields) {
            const element = document.getElementById(field);
            if (element) {
                if (element.value.trim() === '') {
                    firstInvalidField = element;
                    break;
                }
                if (field === 'nombreGerente' && (containsSpecialCharacters(element.value) || !containsOnlyLetters(element.value))) {
                    firstInvalidField = element;
                    break;
                }
                if (field === 'titularidadVehiculo' && (containsSpecialCharacters(element.value) || !containsOnlyLetters(element.value))) {
                    firstInvalidField = element;
                    break;
                }
                if ((field.includes('telefono') || field.includes('telefonoMovil')) && !isValidPhoneNumber(element.value)) {
                    firstInvalidField = element;
                    break;
                }
                if (field !== 'nombreGerente' && containsSpecialCharacters(element.value)) {
                    firstInvalidField = element;
                    break;
                }
            }
        }

        const pdfFileElement = document.getElementById('pdfFile');
        const existingPDF = document.getElementById('existingPdf');
        console.log(pdfFileElement, existingPDF)

        if (!existingPDF && (!pdfFileElement.files || pdfFileElement.files.length === 0)) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo PDF requerido',
                text: 'Por favor, cargue un archivo PDF antes de enviar el formulario.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    pdfFileElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    pdfFileElement.focus();
                }, 500);
            });
            return;
        }
    
        if (!firstInvalidField) {
            for (let i = 0; i < 10; i++) {
                for (let vehiculoField of vehiculosValidar) {
                    const element = document.getElementById(`${vehiculoField}${i}`);
                    if (element && element.value.trim() === '') {
                        firstInvalidField = element;
                        break;
                    }
    
                    if (element) {
                        const elementId = `${vehiculoField}${i}`;
                        if (elementId.startsWith('nroRegistro') || elementId.startsWith('tipoHabilitacion') || elementId.startsWith('tipoSeguro')) {
                            if (!containsOnlyLettersAndNumbers(element.value)) {
                                firstInvalidField = element;
                            }
                        } else if (elementId.startsWith('cantAsientos') || elementId.startsWith('nroPoliza')) {
                            if (!containsOnlyNumbers(element.value)) {
                                firstInvalidField = element;
                            }
                        } else if (elementId.startsWith('vigenciaVTV')) {
                            if (!isValidDate(element.value)) {
                                firstInvalidField = element;
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Campos Incorrectos',
                                    text: `El campo "${firstInvalidField.previousElementSibling.textContent}" posee una fecha no válida (vencida).`,
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    setTimeout(() => {
                                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        firstInvalidField.focus();
                                    }, 500);
                                });
                                return;
                            }
                        } else if (elementId.startsWith('fecha')) {
                            if (isValidDate(element.value)) {
                                firstInvalidField = element;
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Campos Incorrectos',
                                    text: `El campo "${firstInvalidField.previousElementSibling.textContent}" posee una fecha no válida (futura).`,
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    setTimeout(() => {
                                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        firstInvalidField.focus();
                                    }, 500);
                                });
                                return;
                            }
                        }
                    }
                    if (firstInvalidField) break;
                }
                if (firstInvalidField) break;
            }
        }
    
        if (!firstInvalidField) {
            for (let i = 0; i < 10; i++) {
                let dniValue = null, carnetValue = null;
        
                for (let conductorField of conductoresValidar) {
                    const element = document.getElementById(`${conductorField}${i}`);
                    if (element && element.value.trim() === '') {
                        firstInvalidField = element;
                        break;
                    }
        
                    if (element) {
                        const elementId = `${conductorField}${i}`;
                        if (elementId.startsWith('nombreConductor') && !containsOnlyLetters(element.value)) {
                            firstInvalidField = element;
                        } else if (elementId.startsWith('dniConductor')) {
                            if (!containsOnlyNumbers(element.value)) {
                                firstInvalidField = element;
                            } else {
                                dniValue = element.value;
                            }
                        } else if (elementId.startsWith('carnetConducir')) {
                            if (!containsOnlyNumbers(element.value)) {
                                firstInvalidField = element;
                            } else {
                                carnetValue = element.value;
                            }
                        } else if (elementId.startsWith('vigenciaConductor') && !isValidDate(element.value)) {
                            firstInvalidField = element;
                        }
                    }
        
                    if (firstInvalidField) break;
                }
        
                if (dniValue && carnetValue && dniValue !== carnetValue) {
                    firstInvalidField = document.getElementById(`carnetConducir${i}`);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error de Validación',
                        text: `El DNI del conductor no coincide con el número de carnet en el campo "${firstInvalidField.previousElementSibling.textContent}".`,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        setTimeout(() => {
                            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstInvalidField.focus();
                        }, 500);
                    });
                    return;
                }
        
                if (dniValue && carnetValue) {
                    const dniValueLength = dniValue.length;
                    if (dniValueLength < 7 || dniValueLength > 8) {
                        firstInvalidField = document.getElementById(`dniConductor${i}`);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error de Validación',
                            text: `El DNI del conductor y el número de carnet deben poseer entre 7 y 8 dígitos en el campo "${firstInvalidField.previousElementSibling.textContent}".`,
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            setTimeout(() => {
                                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstInvalidField.focus();
                            }, 500);
                        });
                        return;
                    }
                }
        
                if (firstInvalidField) break;
            }
        }
        
        if (firstInvalidField) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos Incorrectos',
                text: `El campo "${firstInvalidField.previousElementSibling.textContent}" contiene una entrada inválida o está incompleto.`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                setTimeout(() => {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }, 500);
            });
            return;
        }
    
        capturarDatos();
        enviarFormulario('formAnexoVIII', '../../php/insertAnexoVIII.php', 'Anexo VIII cargado correctamente!', 'planillaInfo-tab');
    }    

    function validateAndSubmitAnexoPlanilla(event) {
        let firstInvalidField = null;
    
        const otrosCampos = [
            'empresas',
            'datosInfraestructura',
            'hospitalesCercanos',
            'datosInteres',
        ];

        for (let id of otrosCampos) {
            const element = document.getElementById(id);
            if (element && element.value.trim() === '') {
                if (!firstInvalidField) {
                    firstInvalidField = element;
                }
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo Obligatorio',
                    text: `El campo "${element.previousElementSibling.textContent}" es obligatorio.`,
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    setTimeout(() => {
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        element.focus();
                    }, 500);
                });
                event.preventDefault();
                return;
            }
        }
    
        enviarFormulario('formPlanilla', '../../php/insertAnexoPlanilla.php', 'Planilla cargada correctamente!', 'anexov-tab');
    }

    function enviarFormulario(formId, actionUrl, successMessage, nextTabId) {
        var form = document.getElementById(formId);
        if (!form) return;

        var formData = new FormData(form);

        fetch(actionUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                Swal.fire({
                    icon: 'success',
                    title: successMessage,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    if (nextTabId) {
                        document.getElementById(nextTabId).disabled = false;
                        $('#' + nextTabId).tab('show');
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar el anexo',
                    text: data,
                    confirmButtonText: 'Intentar de nuevo'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error en la conexión',
                text: 'No se pudo conectar al servidor. Por favor intenta de nuevo.',
                confirmButtonText: 'Ok'
            });
        });
    }

    document.getElementById('cargarAnexoVIII').addEventListener('click', validateAndSubmitAnexoVIII);
    document.getElementById('cargarPlanilla').addEventListener('click', validateAndSubmitAnexoPlanilla);
});

function generarDatosCargados(data) {
    const item = data[0];  // Asume que solo hay un objeto en el array

    // Divide cada campo en arrays de datos
    const nroRegistroArray = item.nroRegistro.split('%');
    const fechaHabilitacionArray = item.fechaHabilitacion.split('%');
    const tipoHabilitacionArray = item.tipoHabilitacion.split('%');
    const cantAsientosArray = item.cantAsientos.split('%');
    const vigenciaVTVArray = item.vigenciaVTV.split('%');
    const nroPolizaArray = item.nroPoliza.split('%');
    const tipoSeguroArray = item.tipoSeguro.split('%');

    const nombreConductorArray = item.nombreConductor.split('%');
    const dniConductorArray = item.dniConductor.split('%');
    const carnetConducirArray = item.carnetConducir.split('%');
    const vigenciaConductorArray = item.vigenciaConductor.split('%');

    const vehiculosCount = nroRegistroArray.length;
    const conductoresCount = nombreConductorArray.length;

    // Genera los campos de vehículos y conductores con los datos cargados
    generarCamposVehiculos(vehiculosCount, nroRegistroArray, fechaHabilitacionArray, tipoHabilitacionArray, cantAsientosArray, vigenciaVTVArray, nroPolizaArray, tipoSeguroArray);
    generarCamposConductores(conductoresCount, nombreConductorArray, dniConductorArray, carnetConducirArray, vigenciaConductorArray);

    // Actualiza los valores de los selectores de cantidad
    document.getElementById("cantidadVehiculos").value = vehiculosCount;
    document.getElementById("cantidadConductores").value = conductoresCount;

    // Vincula eventos de cambio para los selectores
    document.getElementById("cantidadVehiculos").addEventListener("change", function() {
        generarCamposVehiculos(this.value);  // Genera campos vacíos adicionales si cambia el selector
    });
    document.getElementById("cantidadConductores").addEventListener("change", function() {
        generarCamposConductores(this.value);  // Genera campos vacíos adicionales si cambia el selector
    });
}

function generarCamposVehiculos(vehiculosCount, nroRegistroArray = [], fechaHabilitacionArray = [], tipoHabilitacionArray = [], 
                                cantAsientosArray = [], vigenciaVTVArray = [], nroPolizaArray = [], tipoSeguroArray = []) {
    const vehiculosContainer = document.getElementById("vehiculosContainer");

    // Almacena los valores existentes
    const camposExistentes = [];
    for (let i = 0; i < vehiculosCount; i++) {
        const campo = {
            nroRegistro: document.getElementById(`nroRegistro${i}`)?.value || '',
            fechaHabilitacion: document.getElementById(`fechaHabilitacion${i}`)?.value || '',
            tipoHabilitacion: document.getElementById(`tipoHabilitacion${i}`)?.value || '',
            cantAsientos: document.getElementById(`cantAsientos${i}`)?.value || '',
            vigenciaVTV: document.getElementById(`vigenciaVTV${i}`)?.value || '',
            nroPoliza: document.getElementById(`nroPoliza${i}`)?.value || '',
            tipoSeguro: document.getElementById(`tipoSeguro${i}`)?.value || ''
        };
        camposExistentes.push(campo);
    }

    vehiculosContainer.innerHTML = "";

    for (let i = 0; i < vehiculosCount; i++) {
        const vehiculoHTML = `
            <div class="wrapper">
                <div class="title">Información del vehículo Nº${i + 1}</div>
                <div class="box">
                    <div class="form-group">
                        <p for="nroRegistro${i}" class="form-label">Número de registro del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="nroRegistro${i}" name="nroRegistro${i}" value="${nroRegistroArray[i] || camposExistentes[i]?.nroRegistro || ''}" placeholder="Número de registro...">
                    </div>
                    <div class="form-group">
                        <p for="fechaHabilitacion${i}" class="form-label">Fecha de habilitación del vehículo ${i + 1}:</p>
                        <input type="date" class="form-control item" id="fechaHabilitacion${i}" name="fechaHabilitacion${i}" value="${fechaHabilitacionArray[i] || camposExistentes[i]?.fechaHabilitacion || ''}" placeholder="Fecha de habilitación...">
                    </div>
                    <div class="form-group">
                        <p for="tipoHabilitacion${i}" class="form-label">Tipo de habilitación del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="tipoHabilitacion${i}" name="tipoHabilitacion${i}" value="${tipoHabilitacionArray[i] || camposExistentes[i]?.tipoHabilitacion || ''}" placeholder="Tipo de habilitación...">
                    </div>
                    <div class="form-group">
                        <p for="cantAsientos${i}" class="form-label">Cantidad de asientos del vehículo ${i + 1}:</p>
                        <input type="number" class="form-control item" id="cantAsientos${i}" name="cantAsientos${i}" value="${cantAsientosArray[i] || camposExistentes[i]?.cantAsientos || ''}" placeholder="Cantidad de asientos...">
                    </div>
                    <div class="form-group">
                        <p for="vigenciaVTV${i}" class="form-label">Vigencia de VTV del vehículo ${i + 1}:</p>
                        <input type="date" class="form-control item" id="vigenciaVTV${i}" name="vigenciaVTV${i}" value="${vigenciaVTVArray[i] || camposExistentes[i]?.vigenciaVTV || ''}" placeholder="Vigencia VTV...">
                    </div>
                    <div class="form-group">
                        <p for="nroPoliza${i}" class="form-label">Número de póliza del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="nroPoliza${i}" name="nroPoliza${i}" value="${nroPolizaArray[i] || camposExistentes[i]?.nroPoliza || ''}" placeholder="Número de póliza...">
                    </div>
                    <div class="form-group">
                        <p for="tipoSeguro${i}" class="form-label">Tipo de seguro del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="tipoSeguro${i}" name="tipoSeguro${i}" value="${tipoSeguroArray[i] || camposExistentes[i]?.tipoSeguro || ''}" placeholder="Tipo de seguro...">
                    </div>
                </div>
            </div>
            <br>
        `;
        vehiculosContainer.insertAdjacentHTML('beforeend', vehiculoHTML);
    }
}

function generarCamposConductores(conductoresCount, nombreConductorArray = [], dniConductorArray = [], carnetConducirArray = [], 
                                vigenciaConductorArray = []) {
    const conductoresContainer = document.getElementById("conductoresContainer");

    // Almacena los valores existentes
    const camposExistentes = [];
    for (let i = 0; i < conductoresCount; i++) {
        const campo = {
            nombreConductor: document.getElementById(`nombreConductor${i}`)?.value || '',
            dniConductor: document.getElementById(`dniConductor${i}`)?.value || '',
            carnetConducir: document.getElementById(`carnetConducir${i}`)?.value || '',
            vigenciaConductor: document.getElementById(`vigenciaConductor${i}`)?.value || ''
        };
        camposExistentes.push(campo);
    }

    conductoresContainer.innerHTML = "";

    for (let i = 0; i < conductoresCount; i++) {
        const conductorHTML = `
            <div class="wrapper">
                <div class="title">Información del conductor Nº${i + 1}</div>
                <div class="box">
                    <div class="form-group">
                        <p for="nombreConductor${i}" class="form-label">Nombre del conductor ${i + 1}:</p>
                        <input type="text" class="form-control item" id="nombreConductor${i}" name="nombreConductor${i}" value="${nombreConductorArray[i] || camposExistentes[i]?.nombreConductor || ''}" placeholder="Nombre del conductor...">
                    </div>
                    <div class="form-group">
                        <p for="dniConductor${i}" class="form-label">DNI conductor ${i + 1}:</p>
                        <input type="number" class="form-control item" id="dniConductor${i}" name="dniConductor${i}" value="${dniConductorArray[i] || camposExistentes[i]?.dniConductor || ''}" placeholder="Nombre del conductor...">
                    </div>
                    <div class="form-group">
                        <p for="carnetConducir${i}" class="form-label">Número de carnet del conductor ${i + 1}:</p>
                        <input type="text" class="form-control item" id="carnetConducir${i}" name="carnetConducir${i}" value="${carnetConducirArray[i] || camposExistentes[i]?.carnetConducir || ''}" placeholder="Carnet del conductor..">
                    </div>
                    <div class="form-group">
                        <p for="vigenciaConductor${i}" class="form-label">Vigencia de carnet del conductor ${i + 1}:</p>
                        <input type="date" class="form-control item" id="vigenciaConductor${i}" name="vigenciaConductor${i}" value="${vigenciaConductorArray[i] || camposExistentes[i]?.vigenciaConductor || ''}" placeholder="Vigencia del conductor...">
                    </div>
                </div>
            </div>
            <br>
        `;
        conductoresContainer.insertAdjacentHTML('beforeend', conductorHTML);
    }
}

function generarVehiculos() {
    const cantidad = document.getElementById("cantidadVehiculos").value;
    const container = document.getElementById("vehiculosContainer");
    
    // Limpiar el contenido previo
    container.innerHTML = "";

    // Generar la cantidad de formularios seleccionada
    for (let i = 0; i < cantidad; i++) {
        const formularioHTML = `
            <div class="wrapper">
                <div class="title">Información del vehículo Nº${i + 1}</div>
                <div class="box">
                    <div class="form-group">
                        <p for="nroRegistro${i}" class="form-label">Número de registro del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="nroRegistro${i}" name="nroRegistro${i}" placeholder="Ingrese el número de registro...">
                    </div>
                    <div class="form-group">
                        <p for="fechaHabilitacion${i}" class="form-label">Fecha de habilitación del vehículo ${i + 1}:</p>
                        <input type="date" class="form-control item" id="fechaHabilitacion${i}" name="fechaHabilitacion${i}" placeholder="Seleccione la fecha...">
                    </div>
                    <div class="form-group">
                        <p for="tipoHabilitacion${i}" class="form-label">Tipo de habilitación del Vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="tipoHabilitacion${i}" name="tipoHabilitacion${i}" placeholder="Ingrese el tipo de habilitación...">
                    </div>
                    <div class="form-group">
                        <p for="cantAsientos${i}" class="form-label">Cantidad de asientos del vehículo ${i + 1}:</p>
                        <input type="number" class="form-control item" id="cantAsientos${i}" name="cantAsientos${i}" placeholder="Ingrese la cantidad de asientos...">
                    </div>
                    <div class="form-group">
                        <p for="vigenciaVTV${i}" class="form-label">Vigencia de VTV del vehículo ${i + 1}:</p>
                        <input type="date" class="form-control item" id="vigenciaVTV${i}" name="vigenciaVTV${i}" placeholder="Seleccione la fecha...">
                    </div>
                    <div class="form-group">
                        <p for="nroPoliza${i}" class="form-label">Número de póliza del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="nroPoliza${i}" name="nroPoliza${i}" placeholder="Ingrese el número de póliza...">
                    </div>
                    <div class="form-group">
                        <p for="tipoSeguro${i}" class="form-label">Tipo de seguro del vehículo ${i + 1}:</p>
                        <input type="text" class="form-control item" id="tipoSeguro${i}" name="tipoSeguro${i}" placeholder="Ingrese el tipo de seguro...">
                    </div>
                </div>
            </div>
            <br>
        `;
        container.insertAdjacentHTML('beforeend', formularioHTML);
    }
}

function generarConductores() {
    const cantidadConductor = document.getElementById("cantidadConductores").value;
    const containerConductor = document.getElementById("conductoresContainer");
    
    containerConductor.innerHTML = "";

    for (let i = 0; i < cantidadConductor; i++) {
        const formularioHTML = `
            <div class="wrapper">
                <div class="title">Información del conductor Nº${i + 1}</div>
                <div class="box">
                    <div class="form-group">
                        <p for="nombreConductor${i}" class="form-label">Nombre del conductor ${i + 1}:</p>
                        <input type="text" class="form-control item" id="nombreConductor${i}" name="nombreConductor${i}" placeholder="Ingrese el nombre del conductor...">
                    </div>
                    <div class="form-group">
                        <p for="dniConductor${i}" class="form-label">DNI conductor ${i + 1}:</p>
                        <input type="number" class="form-control item" id="dniConductor${i}" name="dniConductor${i}" placeholder="Ingrese el DNI del conductor...">
                    </div>
                    <div class="form-group">
                        <p for="carnetConducir${i}" class="form-label">Número de carnet del conductor ${i + 1}:</p>
                        <input type="text" class="form-control item" id="carnetConducir${i}" name="carnetConducir${i}" placeholder="Ingrese el carnet del conductor...">
                    </div>
                    <div class="form-group">
                        <p for="vigenciaConductor${i}" class="form-label">Vigencia de carnet del conductor ${i + 1}:</p>
                        <input type="date" class="form-control item" id="vigenciaConductor${i}" name="vigenciaConductor${i}" placeholder="Ingrese la fecha...">
                    </div>
                </div>
            </div>
            <br>
        `;
        containerConductor.insertAdjacentHTML('beforeend', formularioHTML);
    }
}