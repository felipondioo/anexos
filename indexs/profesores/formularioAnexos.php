<?php
    include '../../php/verificarSessionProfesores.php';
    error_reporting(0);

    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
    $idSalida = $_SESSION['idSalida'];
    if (!isset($idSalida)) {
        die("No se encontró idSalida en la sesión.");
    }

    $query = "SELECT estado FROM anexoiv WHERE idAnexoIV = ?";
    $stmt = $conexion->prepare($query);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $idSalida);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            if ($row['estado'] == 3) {
                // Si el estado es 3, redirige a menuSalidas
                header('Location: menuAdministrarSalidas.php');
                exit;
            } else {
                // echo "El estado no es 3. Estado actual: " . $row['estado'];
            }
        } else {
            // echo "No se encontraron resultados.";
        }
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu de Salidas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/anexos.css">
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
                    <h2 style="color: black;">Menú de Anexos</h2>
                </div>
                <br>
                
                <?php include("../../php/traerEstadoAnexos.php")?>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="anexoV-tab" data-bs-toggle="tab" data-bs-target="#anexoV" type="button" role="tab" aria-controls="anexoV" aria-selected="true">Anexo V</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="anexoVIII-tab" data-bs-toggle="tab" data-bs-target="#anexoVIII" type="button" role="tab" aria-controls="anexoVIII" aria-selected="false">Anexo VIII</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="planillaInfo-tab" data-bs-toggle="tab" data-bs-target="#planillaInfo" type="button" role="tab" aria-controls="planillaInfo" aria-selected="false">Planilla Informativa</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="myTabContent"> 
                    <?php include('../../php/verificarInsertProfesorAV.php'); ?>
                    
                    <!-- Anexo 5 -->
                        <div class="tab-pane fade show active" id="anexoV" role="tabpanel" aria-labelledby="anexoV-tab">
                        <br>
                        <center><h2>Anexo V</h2></center>
                        <br>
                        <form id="formAnexo5" class="formulario" action="../../php/insertAnexoV.php" method="POST">
                            <!-- Sección de DNI -->
                            <div class="row mb-4">
                                <div class="col-md-4 col-12">
                                    <label for="dniSearch" class="form-label">Insertar alumno / docente:</label>
                                    <input type="number" class="form-control item-anexo8" id="dniSearch" min = "0" oninput="validity.valid||(value='');" name="dniSearch" required pattern="\d{8}" placeholder="Insertar por DNI...">
                                </div>
                                <div class="col-md-4 col-12 mt-4 mt-md-0">
                                    <label for="coincidenciaPersona" class="form-label">Coincidencias:</label>
                                    <select class="form-control item-anexo8" name="coincidenciaPersona" id="coincidenciaPersona"></select>
                                </div>
                                <div class="col-md-4 col-12 mt-md-0 mt-4 d-flex align-items-end">
                                    <button type="button" id="agregarPersona" class="cargar w-100">Cargar persona</button>
                                </div>
                            </div>

                            <!-- Sección de Grupos -->
                            <div class="row mb-4">
                                <div class="col-md-8 col-12">
                                    <label for="grupos" class="form-label">Insertar por grupo:</label>
                                    <select id="grupos" name="grupos" class="form-control item-anexo8" style="cursor: pointer;" required>
                                        <?php include ('../../php/traerGrupos.php'); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-12 d-flex mt-4 align-items-end">
                                    <button type="button" class="cargar w-100" id="cargarGrupo">Cargar grupo</button>
                                </div>
                            </div>

                            <!-- Sección de Acompañante -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="acompanante" class="form-label">Insertar no docente:</label>
                                </div>
                                <div class="col-md-2 col-12 mt-2">
                                    <input type="text" class="form-control item" id="dniAcompañante" name="dniAcompañante" placeholder="DNI..." required pattern="\d{8}">
                                </div>
                                <div class="col-md-4 col-12 mt-2">
                                    <input type="text" class="form-control item" id="nombreAcompañante" name="nombreAcompañante" placeholder="Nombre y apellido..." required>
                                </div>
                                <div class="col-md-2 col-12 mt-2">
                                    <input type="number" class="form-control item" id="edadAcompañante" name="edadAcompañante" placeholder="Edad..." required>
                                </div>
                                <div class="col-md-4 col-12 mt-1">
                                    <button id="cargarAcompañante" type="button" class="cargar w-100">Cargar no docente</button>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="suplente" class="form-label">Insertar docente suplente:</label>
                                </div>
                                <div class="col-md-2 col-12 mt-2">
                                    <input type="text" class="form-control item" id="dniSuplente" name="dniSuplente" placeholder="DNI..." required pattern="\d{8}">
                                </div>
                                <div class="col-md-4 col-12 mt-2">
                                    <input type="text" class="form-control item" id="nombreSuplente" name="nombreSuplente" placeholder="Nombre y apellido..." required>
                                </div>
                                <div class="col-md-2 col-12 mt-2">
                                    <input type="number" class="form-control item" id="edadSuplente" name="edadSuplente" placeholder="Edad..." required>
                                </div>
                                <div class="col-md-4 col-12 mt-1">
                                    <button id="cargarSuplente" type="button" class="cargar w-100">Cargar suplente</button>
                                </div>
                            </div>

                            <div class="wrapper-anexov">
                                <div class="title" id="advice-title"></div>
                                <div class="box" id="advice" style="margin-top: 20px;">
                                </div>
                                <p style="margin-top: 15px;"><b>Atención:</b> Este panel es solamente una guía, la aprobación depende del Director.</p>
                            </div>

                            <br>
                            <div class="col-12 d-flex flex-row-reverse mb-1">
                                <button type="button" id="selectAll" class="cargar-anexo8">
                                    Seleccionar todo
                                </button>
                                <button type="button" id="eliminarSeleccionados" class="eliminar-objetivo mx-1">
                                    Eliminar
                                </button>
                            </div>

                            <!-- Tabla de Participantes -->
                            <div class="table-responsive" style="text-align: center;">
                                <table class="table table-striped-columns">
                                    <thead>
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Apellido y nombre</th>
                                            <th scope="col">Documento</th>
                                            <th scope="col">Edad</th>
                                            <th scope="col">Alumno</th>
                                            <th scope="col">Docente</th>
                                            <th scope="col">No docente</th>
                                            <th scope="col">Docente suplente</th>
                                            <th scope="col">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaParticipantes">
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="anexoVIII" role="tabpanel" aria-labelledby="anexoVIII-tab">
                        <form id="formAnexoVIII" class="formulario" enctype="multipart/form-data">
                            <br>
                            <center><h2>Anexo VIII</h2></center>
                            <br>
                            <?php include ("../../php/traerAnexoVIII.php"); ?>
                        </form>
                        <div class="form-group d-flex justify-content-center">
                            <button type="submit" class="cargar" id="cargarAnexoVIII">Cargar Anexo VIII</button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="planillaInfo" role="tabpanel" aria-labelledby="planillaInfo-tab">
                        <br>
                        <form id="formPlanilla" class="formulario" id="cargarPlanilla">
                            <center><h2>Planilla Informativa</h2></center>
                            <br>
                            <?php include ("../../php/traerAnexoPlanilla.php"); ?>
                        </form>
                        <div class="form-group d-flex justify-content-center">
                            <button type="submit" class="btn btn-block cargar" id="cargarPlanilla">Cargar planilla informativa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="../../js/anexoV.js"></script>
        <script src="../../js/enviarFormularios.js"></script>
        <script>
            var salidaIDSesion = "<?php echo $idSalida; ?>";
            var anexoVIIIHabil = "<?php echo $anexoviiiHabil; ?>";  
            var planillaHabil = "<?php echo $planillaHabil; ?>";  
        </script>
    </body>
</html>