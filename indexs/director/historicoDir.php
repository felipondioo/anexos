<?php
    include '../../php/verificarSessionDirector.php';
    
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
    $dni = $_SESSION['dniDirector'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de Salidas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/historico.css">
        <style>
            /* Opcional: Estilo adicional para mejorar la visualización */
            @media (max-width: 768px) {
                h1 {
                    font-size: 1.5rem; /* Tamaño de fuente más pequeño en pantallas pequeñas */
                }
            }
        </style>
    </head>
    <nav class="navbar navbar-custom">
        <div class="container-fluid d-flex align-items-center">
            <a onclick="window.history.back();" class="btn btn-warning ms-auto" style="color: white;">Atrás</a>
        </div>
    </nav>
    <body>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-md">
                    <h1>Histórico de salidas educativas</h1>
                    <hr>
                    <div class="table-responsive"> <!-- Añadir div para la tabla responsive -->
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Tipo de salida</th>
                                    <th>Denominación</th>
                                    <th>Estado final</th>
                                    <th>Duración de la salida</th>
                                    <th>Lugar visita</th>
                                    <th>Fecha salida</th>
                                    <th>Fecha regreso</th>
                                    <th>¿Anexo VIII?</th>
                                    <th>Fecha límite (entrega)</th>
                                    <th>Fecha de gestión (Director)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include('../../php/traerHistoricoDir.php'); ?>
                            </tbody>
                        </table>
                    </div> <!-- Cierre del div table-responsive -->
                </div>
            </div>
            <?php if ($historico == false) {echo '<h5>No hay registros de salidas educativas...</h5>';}?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
