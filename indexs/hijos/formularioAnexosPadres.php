<?php
    include '../../php/verificarSessionPadres.php';

    $dniAlumno = $_SESSION['dniAlumno'];
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
    $idSalida = $_SESSION['idSalida'];
    error_reporting(0);

    // echo $dniAlumno;
    // echo $idSalida;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anexo VI - Salidas Educativas</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/padresAnexos.css">
    </head>
    <body>
        <nav class="navbar navbar-custom">
            <div class="container-fluid d-flex align-items-center">
                <a onclick="window.history.back();" class="btn btn-warning ms-auto"  style="color: white; font-family: system-ui;">Atrás</a>
            </div>
        </nav>

        <div class="registration-form">
            <div class="formulario-container">
                <div class="form-icon">
                    <span><i class="icon-heart"></i></span>
                </div>
                <div style="text-align: center;">
                    <h2 style="color: black;">Anexo 6</h2>
                    <h4 style="color: black;">(Constancia médica)</h4>
                </div>
                <br>
                <p style="margin-top: 10px; margin-bottom: 15px;"><b>Atención:</b> Para reemplazar los datos solo debe modificarlos y subir el formulario nuevamente. Los datos que usted no complete, se mostrarán automáticamente con un guión medio (-) en el PDF.</p>

                <?php include('../../php/traerAnexoVI.php') ?>
                <center><button type="submit" class="cargar" id="cargarAnexoVI">Cargar Anexo 6</button></center>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="../../js/enviarFormulariosPadres.js"></script>
        <script>
            function toggleObraSocialInput(show) {
                document.getElementById('nomInput').style.display = show ? 'block' : 'none';
                document.getElementById('nroInput').style.display = show ? 'block' : 'none';
            }
        </script>
    </body>
</html>