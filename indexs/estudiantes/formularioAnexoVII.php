<?php
    include '../../php/verificarSessionEstudiantes.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anexo VII - Salidas Educativas</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
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
                    <span><i class="icon-notebook"></i></span>
                </div>
                <div style="text-align: center;">
                    <h2 style="color: black;">Anexo VII</h2>
                    <h4 style="color: black;">(Declaración jurada)</h4>
                </div>
                <br>
                <p style="margin-top: 10px; margin-bottom: 15px;"><b>Atención:</b> Para reemplazar los datos solo debe modificarlos y subir el formulario nuevamente. Los datos que usted no complete, se mostrarán automáticamente con un guión medio (-) en el PDF.</p>
                <div class="tab-content mt-3" id="myTabContent"> 
                    <!-- Anexo 7 -->
                    <div class="tab-pane fade show active" id="anexo7" role="tabpanel" aria-labelledby="anexo7-tab">
                        <div id="formAnexoVII">
                            <?php 
                            include ("../../php/traerAnexoVII.php"); ?>
                            <br><br>
                            <center><button type="submit" class="cargar" id="cargarAnexoVII">Cargar Anexo 7</button></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            
        // Obtener los elementos
        const obraSocialSi = document.getElementById('obraSi');
        const obraSocialNo = document.getElementById('obraNo');
        const obraSocialNombreDiv = document.getElementById('obraSocialNombreDiv');
        const obraSocialNumeroDiv = document.getElementById('obraSocialNumeroDiv');

        // Función para mostrar u ocultar los campos de obra social
        function toggleObraSocialFields() {
            if (obraSocialSi.checked) {
                // Mostrar los campos si "Sí" está seleccionado
                obraSocialNombreDiv.style.display = 'block';
                obraSocialNumeroDiv.style.display = 'block';
            } else {
                // Ocultar los campos si "No" está seleccionado
                obraSocialNombreDiv.style.display = 'none';
                obraSocialNumeroDiv.style.display = 'none';
            }
        }

        // Agregar eventos de cambio a los radio buttons
        obraSocialSi.addEventListener('change', toggleObraSocialFields);
        obraSocialNo.addEventListener('change', toggleObraSocialFields);

        // Llamar a la función al cargar la página para establecer el estado inicial
        toggleObraSocialFields();
    </script>
        <script src="../../js/enviarFormularioEstudiantes.js"></script>
    </body>
</html>