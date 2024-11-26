<?php
    include '../../php/verificarSessionProfesores.php';

    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

    $dniProfesor = $_SESSION['dniProfesor'];
    $nombrePro = $_SESSION['nombreDoc'];
    $apellidoPro = $_SESSION['apellidoDoc'];
    $nombreCompletoProfesor = $nombrePro." ".$apellidoPro;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Anexo IV - Salidas Educativas</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/anexoIV.css">
    </head>
    <body>
        <nav class="navbar navbar-custom">
            <div class="container-fluid d-flex align-items-center">
                <a href="menuAdministrarSalidas.php" class="btn btn-warning ms-auto" style="color: white; font-family: system-ui">Atrás</a>
            </div>
        </nav>

        <div class="registration-form">
            <form id="formAnexoIV" class="formulario" action="../../php/insertAnexoIV.php" method="POST">
                <div class="form-icon">
                    <span><i class="icon icon-notebook"></i></span>
                </div>
                <div style="text-align: center;">
                    <h2 style="color: black;">Anexo IV</h2>
                </div>
                <br>
                <div class="form-group-flex">
                    <div class="wrapper" style="max-width: 500px;">
                        <div class="title">¿Utilizará el Anexo VIII?</div>
                        <div class="box">
                            <input type="radio" id="option-1" name="anexoVIII" value="1">
                            <input type="radio" id="option-2" name="anexoVIII" value="2">

                            <label for="option-1" class="option-1">
                                <div class="dot"></div>
                                <div class="secundario">Si</div>
                            </label>
                            <label for="option-2" class="option-2">
                                <div class="dot"></div>
                                <div class="secundario">No</div>
                            </label>
                        </div>
                    </div>
                    <div class="wrapper" style="max-width: 500px;">
                        <div class="title">¿Tipo de Salida?</div>
                        <div class="box">
                            <input type="radio" id="option-3" name="tipoSalida" value="1">
                            <input type="radio" id="option-4" name="tipoSalida" value="2">
                            <label for="option-3" class="option-3">
                                <div class="dot"></div>
                                <div class="secundario">Representación Institucional</div>
                            </label>
                            <label for="option-4" class="option-4">
                                <div class="dot"></div>
                                <div class="secundario">Educativa</div>
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="wrapper">
                    <div class="title">¿Distancia de la Salida?</div>
                    <div class="box">
                        <input type="radio" id="option-5" name="distanciaSalida" value="1">
                        <input type="radio" id="option-6" name="distanciaSalida" value="2">
                        <input type="radio" id="option-7" name="distanciaSalida" value="3">
                        <input type="radio" id="option-8" name="distanciaSalida" value="4">
                        <input type="radio" id="option-9" name="distanciaSalida" value="5">

                        <label for="option-5" class="option-5">
                            <div class="dot"></div>
                            <div class="text"></div>
                        </label>

                        <label for="option-6" class="option-6">
                            <div class="dot"></div>
                            <div class="text"></div>
                        </label>
                        
                        <label for="option-7" class="option-7">
                            <div class="dot"></div>
                            <div class="text"></div>
                        </label>

                        <label for="option-8" class="option-8">
                            <div class="dot"></div>
                            <div class="text"></div>
                        </label>
                        
                        <label for="option-9" class="option-9">
                            <div class="dot"></div>
                            <div class="text"></div>
                        </label>
                    </div>
                </div>
                <br><br>
                <div class="form-group">
                    <label for="denominacionProyecto" class="form-label">Denominación del Proyecto:</label>
                    <input type="text" class="form-control item" id="denominacionProyecto" name="denominacionProyecto" placeholder="Ingrese la denominación del proyecto..." required>
                </div>

                <div class="form-group">
                    <label for="lugarVisita" class="form-label">Lugar a Visitar:</label>
                    <input type="text" class="form-control item" id="lugarVisita" name="lugarVisita" placeholder="Ingrese el lugar a visitar..." required>
                </div>

                <div class="form-group">
                    <label for="direccionVisita" class="form-label">Dirección a Visitar:</label>
                    <input type="text" class="form-control item" id="direccionVisita" name="direccionVisita" placeholder="Ingrese la dirección a visitar..." required>
                </div>

                <div class="form-group">
                    <label for="localidadVisita" class="form-label">Localidad a Visitar:</label>
                    <input type="text" class="form-control item" id="localidadVisita" name="localidadVisita" placeholder="Ingrese la localidad a visitar..." required>
                </div>

                <div class="form-group">
                    <label for="regionVisita" class="form-label">Región a Visitar (Número):</label>
                    <input type="number" class="form-control item" id="regionVisita" name="regionVisita" placeholder="Ingrese el número de región a visitar..." required>
                </div>

                <div class="form-group">
                    <label for="fechaSalida" class="form-label">Fecha de Salida:</label>
                    <input type="date" class="form-control item" id="fechaSalida" name="fechaSalida" required disabled>
                    <p><b>ATENCIÓN:</b> Tenga en cuenta de que si usted está en la fecha límite, tiene hasta las 12:00 del mediodía para presentar el proyecto. Si no cumple con los plazos de la Reforma Provincial, se inhabilitará la salida y deberá empezar una nueva.</p>
                </div>

                <div class="form-group">
                    <label for="fechaLimite" class="form-label">Fecha límite (Cálculo automático):</label>
                    <input type="datetime-local" class="form-control item" id="fechaLimite" name="fechaLimite" required readonly>
                </div>  

                <div class="form-group">
                    <label for="lugarSalida" class="form-label">Lugar de Salida:</label>
                    <input type="text" class="form-control item" id="lugarSalida" name="lugarSalida" placeholder="Ingrese el lugar de salida..." required>
                </div>

                <div class="form-group">
                    <label for="horaSalida" class="form-label">Hora de Salida:</label>
                    <input type="time" class="form-control item" id="horaSalida" name="horaSalida" required disabled>
                </div>

                <div class="form-group">
                    <label for="fechaRegreso" class="form-label">Fecha de Regreso:</label>
                    <input type="date" class="form-control item" id="fechaRegreso" name="fechaRegreso" required disabled>
                </div>

                <div class="form-group">
                    <label for="lugarRegreso" class="form-label">Lugar de Regreso:</label>
                    <input type="text" class="form-control item" id="lugarRegreso" name="lugarRegreso" placeholder="Ingrese el lugar de regreso..." required>
                </div>

                <div class="form-group">
                    <label for="horaRegreso" class="form-label">Hora de Regreso:</label>
                    <input type="time" class="form-control item" id="horaRegreso" name="horaRegreso" required disabled>
                </div>

                <div class="form-group">
                    <label for="itinerario" class="form-label">Itinerario:</label>
                    <textarea class="form-control item" id="itinerario" name="itinerario" rows="3" placeholder="Describa el itinerario..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="actividades" class="form-label">Actividades:</label>
                    <textarea class="form-control item" id="actividades" name="actividades" rows="3" placeholder="Describa las actividades..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="objetivosSalida" class="form-label">Objetivos de la salida:</label>
                    <textarea class="form-control item" id="objetivosSalida" name="objetivosSalida" rows="3" placeholder="Describa los objetivos de la salida..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="cronograma" class="form-label">Cronograma diario:</label>
                    <textarea class="form-control item" id="cronograma" name="cronograma" rows="3" placeholder="Describa el cronograma..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="dniEncargado" class="form-label">DNI del docente:</label>
                    <input type="number" class="form-control item" id="dniEncargado" name="dniEncargado" placeholder="Ingrese el DNI del encargado..." required readonly pattern="\d{8,}" value="<?php echo $dniProfesor?>">
                </div>

                <div class="form-group">
                    <label for="nombreEncargado" class="form-label">Nombre del docente:</label>
                    <input type="text" class="form-control item" id="nombreEncargado" name="nombreEncargado" placeholder="Ingrese el nombre del encargado..." required readonly pattern="[A-Za-z\s]+" value="<?php $nombreFormateadoProfe = ucwords(strtolower($nombreCompletoProfesor)); echo $nombreFormateadoProfe;?>">
                </div>

                <div class="form-group">
                    <label for="nombreHospedaje" id="nH" class="form-label">Nombre del hospedaje:</label>
                    <input type="text" class="form-control item" id="nombreHospedaje" name="nombreHospedaje" placeholder="Ingrese el nombre del hospedaje..." required>
                </div>

                <div class="form-group">
                    <label for="domicilioHospedaje" id="dH" class="form-label">Domicilio del hospedaje:</label>
                    <input type="text" class="form-control item" id="domicilioHospedaje" name="domicilioHospedaje" placeholder="Ingrese el domicilio del hospedaje..." required>
                </div>

                <div class="form-group">
                    <label for="telefonoHospedaje" id="tH" class="form-label">Teléfono del hospedaje:</label>
                    <input type="number" class="form-control item" id="telefonoHospedaje" name="telefonoHospedaje" placeholder="Ingrese el teléfono del hospedaje..." required pattern="\d{11}">
                </div>

                <div class="form-group">
                    <label for="localidadHospedaje" id="lH" class="form-label">Localidad del hospedaje:</label>
                    <input type="text" class="form-control item" id="localidadHospedaje" name="localidadHospedaje" placeholder="Ingrese la localidad del hospedaje..." required>
                </div>

                <div class="form-group">
                    <label for="gastosEstimativos" id="gE" class="form-label">Gastos Estimativos:</label>
                    <textarea type="text" class="form-control item" id="gastosEstimativos" name="gastosEstimativos" placeholder="Ingrese los gastos estimativos o como solventarlos..." required></textarea>
                </div>

                <p style="margin-top: 15px;"><b style="color: red;">ATENCIÓN:</b> Deberá completar la lista del anexo V, para así poder descargar el anexo IV y evitar impresiones incorrectas.</p>

                <div class="form-group">
                    <center><button type="button" class="create-account" id="cargarAnexoIV">Cargar Anexo 4</button></center>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/date-fns@4.1/cdn.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../../js/anexoIV.js"></script>    
    </body>
</html>