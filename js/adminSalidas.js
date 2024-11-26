$(document).ready(function() {
    function formatearFecha(fecha, incluirHora = false) {
        if (!fecha) return '';
        
        let partesFechaHora = fecha.split(' '); // Separar la fecha y la hora
        let soloFecha = partesFechaHora[0]; // Toma solo la parte de la fecha (antes del espacio)
        let partesFecha = soloFecha.split('-'); // Dividir la fecha por guiones
        
        // Formatear la fecha en dd/mm/yyyy
        let fechaFormateada = `${partesFecha[2]}/${partesFecha[1]}/${partesFecha[0]}`;
        
        if (incluirHora && partesFechaHora.length > 1) {
            // Si se debe incluir la hora y la fecha tiene hora, concatenarla
            let hora = partesFechaHora[1]; // La parte después del espacio es la hora
            fechaFormateada += ` ${hora}`; // Concatenar la hora al formato de fecha
        }

        return fechaFormateada;
    }
    
    
    $.ajax({
        url: '../../php/tablaAdminSalidas.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let tabla = $('#proyectosTabla');
            // Recorrer los datos y generar las filas
            data.forEach(function(salida) {
                if(salida.distanciaSalida == 1 || salida.distanciaSalida == 2){
                    let fila = `
                        <tr>
                            <td>${salida.denominacionProyecto}</td>
                            <td>${salida.tipoSolicitud}</td>
                            <td>${salida.lugarVisita}</td>
                            <td>${formatearFecha(salida.fechaSalida)}</td> <!-- Solo fecha -->
                            <td>${formatearFecha(salida.fechaRegreso)}</td> <!-- Solo fecha -->
                            <td>${salida.anexoixHabil}</td>
                            <td>${formatearFecha(salida.fechaSolicitud, true)}</td> <!-- Fecha con hora -->
                            <td>${formatearFecha(salida.fechaLimite, true)}</td> <!-- Fecha con hora -->
                            <td><a class="btn btn-primary" href="../pdf/plantillaPDFInspector.php" onclick="establecerIdSalida(${salida.idAnexoIV})">Descargar</a></td>
                            <td>(Mismo PDF para ambos)</td>
                            <td><button class="btn btn-success" onclick="gestionarSalida(${salida.idAnexoIV}, event)">Gestionar</button></td>
                            <td><button class="btn btn-danger" onclick="eliminarSalida(${salida.idAnexoIV}, event)">Eliminar</button></td>
                        </tr>
                    `;
                    tabla.append(fila);
                } else {
                    let fila = `
                        <tr>
                            <td>${salida.denominacionProyecto}</td>
                            <td>${salida.tipoSolicitud}</td>
                            <td>${salida.lugarVisita}</td>
                            <td>${formatearFecha(salida.fechaSalida)}</td> <!-- Solo fecha -->
                            <td>${formatearFecha(salida.fechaRegreso)}</td> <!-- Solo fecha -->
                            <td>${salida.anexoixHabil}</td>
                            <td>${formatearFecha(salida.fechaSolicitud, true)}</td> <!-- Fecha con hora -->
                            <td>${formatearFecha(salida.fechaLimite, true)}</td> <!-- Fecha con hora -->
                            <td><a class="btn btn-primary" href="../pdf/plantillaPDFInspector.php" onclick="establecerIdSalida(${salida.idAnexoIV})">Descargar</a></td>
                            <td><a class="btn btn-primary" href="../pdf/plantillaPDFDirector.php" onclick="establecerIdSalida(${salida.idAnexoIV})">Descargar</a></td>
                            <td><button class="btn btn-success" onclick="gestionarSalida(${salida.idAnexoIV}, event)">Gestionar</button></td>
                            <td><button class="btn btn-danger" onclick="eliminarSalida(${salida.idAnexoIV}, event)">Eliminar</button></td>
                        </tr>
                    `;
                    tabla.append(fila);
                }
            });
            
        },
        error: function(err) {
            console.log('Error al obtener los datos:', err);
        }
    });
});

function establecerIdSalida(idSalida) {
    $.ajax({
        url: '../../php/guardarIdSalida.php',  // Ruta del archivo PHP
        type: 'POST',
        data: { idSalida: idSalida },
        success: function() {

        },
        error: function() {
            alert("Hubo un error al establecer la sesión.");
        }
    });
}


function gestionarSalida(id, event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado
    // Mostrar un alert con tres botones
    Swal.fire({
        title: '¿Qué acción quiere realizar?',
        text: 'Elija si desea aprobar, desaprobar o cancelar la salida',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true, // Botón extra
        confirmButtonText: 'Aprobar',
        denyButtonText: 'Desaprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Lógica para aprobar salida
            aprobarSalida(id);
        } else if (result.isDenied) {
            // Lógica para desaprobar salida
            desaprobarSalida(id);
        } else {
            // Si se cancela, no se realiza ninguna acción.
            Swal.fire('Acción cancelada', '', 'info');
        }
    });
}

function aprobarSalida(id) {
    console.log('Aprobando salida con ID:', id);
    $.post('../../php/aprobarSalida.php', { idAnexoIV: id }, function(response) {
        console.log('Respuesta AJAX:', response); // Debug
        try {
            const res = JSON.parse(response);
            if (res.status === 'success') {
                Swal.fire({
                    title: 'Aprobada',
                    text: res.message,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        } catch (e) {
            console.log('Error al parsear respuesta:', e); // Debug
            Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
        }
    });
}

function desaprobarSalida(id) {
    console.log('Desaprobando salida con ID:', id);
    $.post('../../php/desaprobarSalida.php', { idAnexoIV: id }, function(response) {
        console.log('Respuesta AJAX:', response); // Debug
        try {
            const res = JSON.parse(response);
            if (res.status === 'success') {
                Swal.fire({
                    title: 'Desaprobada',
                    text: res.message,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        } catch (e) {
            console.log('Error al parsear respuesta:', e); // Debug
            Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
        }
    });
}

function eliminarSalida(id, event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado
    console.log('Eliminando salida con ID: ', id);
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la salida permanentemente, incluyendo todo registro asociado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Confirmación recibida, enviando solicitud AJAX'); // Debug
            // Lógica para eliminar salida
            $.post('../../php/eliminarSalida.php', { idAnexoIV: id }, function(response) {
                console.log('Respuesta AJAX:', response); // Debug
                try {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        Swal.fire({
                            title: 'Eliminada',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                } catch (e) {
                    console.log('Error al parsear respuesta:', e); // Debug
                    Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                }
            });
        }
    });
}
