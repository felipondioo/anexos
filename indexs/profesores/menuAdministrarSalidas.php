<?php
    include '../../php/verificarSessionProfesores.php';
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
    $dni = $_SESSION['dniProfesor'];
    // echo $_SESSION['dniProfesor'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu de Salidas</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="../../css/menuAdminSalidas.css">
    </head>
    <body>
        <nav class="navbar navbar-custom">
            <div class="container-fluid d-flex align-items-center">
                <a href="profesores.php" class="btn btn-warning ms-auto" style="color: white;">Atrás</a>
            </div>
        </nav>

        <div class="container">
            <h1>Administrar Salidas</h1>
            <br>
            <h2 class="subtitulo">Nuevas salidas</h2>
            <a href="formularioAnexoIV.php" class="btn-success form-control botones w-100 mb-5" style="color: white;">Crear Salida</a>

            <div class="row mt-5">
                <div class="col-md-6">
                    <h3 class="subtitulo">Salidas Aprobadas</h3>
                    <hr>
                    <ul>
                        <?php include('../../php/traerSalidasAprobadasProfesor.php'); ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3 class="subtitulo">Salidas Pendientes</h3>
                    <hr>
                    <ul>
                        <?php include('../../php/traerSalidasPendientesProfesor.php'); ?>
                    </ul>
                </div>
            </div>
            <br>
            <a href="historico.php" class="btn-primary form-control botones w-100 mb-5" style="color: white;">Histórico de mis salidas</a>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                window.enviarSalida = function(id) {
                    Swal.fire({
                        title: '¿Enviar solicitud?',
                        text: "Se enviará la solicitud de la salida al Director.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, enviar',
                        cancelButtonText: 'No, cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Enviando salida con ID:', id);
                            $.post('../../php/enviarSalida.php', { idAnexoIV: id }, function(response) {
                                console.log('Respuesta AJAX:', response);
                                try {
                                    const res = JSON.parse(response);
                                    if (res.status === 'success') {
                                        Swal.fire({
                                            title: 'Solicitud Enviada',
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
                                    console.log('Error al parsear respuesta:', e);
                                    Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                                }
                            });
                        }
                    });
                }

                window.cancelarSalida = function(id) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¿Deseas cancelar esta salida?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, cancelar',
                        cancelButtonText: 'No, mantener'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Cancelando salida con ID:', id);
                            $.post('../../php/cancelarSalida.php', { idAnexoIV: id }, function(response) {
                                console.log('Respuesta AJAX:', response);
                                try {
                                    const res = JSON.parse(response);
                                    if (res.status === 'success') {
                                        Swal.fire({
                                            title: 'Salida Cancelada',
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
                                    console.log('Error al parsear respuesta:', e);
                                    Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                                }
                            });
                        }
                    });
                }

                window.infoSalida = function(id) {
                    console.log('Obteniendo información de salida con ID:', id);
                    $.post('../../php/infoSalida.php', { idAnexoIV: id }, function(response) {
                        console.log('Respuesta AJAX:', response);
                        try {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                // Formatear la fecha
                                const fechaLimite = new Date(res.fechaLimite); // Asegúrate de que res.fechaLimite sea un string de fecha válido
                                const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                                const fechaFormateada = fechaLimite.toLocaleDateString('es-ES', opciones); // Cambia 'es-ES' si necesitas otro idioma

                                Swal.fire({
                                    title: 'Fecha Límite',
                                    text: `La fecha límite es el ${fechaFormateada} a las 12:00 del mediodía. Tenga en cuenta que al pasar esta fecha y hora, la salida se calcelará automáticamente.`,
                                    icon: 'info',
                                    confirmButtonText: 'Ok'
                                }).then(() => {
                                    location.reload(); // Recarga la página si es necesario
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        } catch (e) {
                            console.log('Error al parsear respuesta:', e);
                            Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                        }
                    });
                }

                // Función para verificar si se debe cancelar la salida
                function verificarCancelaciones() {
                    $.post('../../php/obtenerSalidasPendientes.php', {}, function(response) {
                        try {
                            const salidas = JSON.parse(response);
                            salidas.forEach(salida => {
                                const fechaLimite = new Date(salida.fechaLimite); // Fecha y hora límite de la salida
                                const fechaActual = new Date(); // Fecha y hora actual

                                // Comparar fecha y hora (no solo fecha)
                                if (fechaActual >= fechaLimite) {
                                    $.post('../../php/cancelarAutomatico.php', { idAnexoIV: salida.idAnexoIV }, function(response) {
                                        try {
                                            const res = JSON.parse(response);
                                            if (res.status === 'success') {
                                                Swal.fire({
                                                    title: 'Salida Cancelada',
                                                    text: res.message,
                                                    icon: 'error',
                                                    confirmButtonText: 'Ok'
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            } else {
                                                console.log('Error al cancelar salida:', res.message);
                                            }
                                        } catch (e) {
                                            console.log('Error al parsear respuesta:', e);
                                        }
                                    });
                                }
                            });
                        } catch (e) {
                            console.log('Error al parsear respuesta:', e);
                        }
                    });
                }
                // Intervalo para verificar cada cierto tiempo
                setInterval(verificarCancelaciones, 1000);
            });
        </script>
    </body>
</html>
