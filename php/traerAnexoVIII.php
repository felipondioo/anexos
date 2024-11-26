<?php
    $idSalida = $_SESSION['idSalida'] ?? null;
    include('conexion.php');

    if (isset($idSalida)) {
        $sql = "SELECT * FROM anexoviii WHERE fkAnexoIV = ?";
        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            error_log('Error preparando la consulta: ' . $conexion->error);
            die('Error interno del servidor.');
        }

        $stmt->bind_param('i', $idSalida);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $existingPdfPath = isset($row['rutaPDF']) ? '../'.$row['rutaPDF'] : null;
            } 

            echo '
                <p style="margin-left: 2px;"><b>Atención:</b> La información completada no debe contenter caracteres especiales. Los nombres propios no pueden contener números y las fechas de vigencia deben ser actuales.</p>    
                <div class="form-group">
                    <label for="nombreEmpresa" class="form-label">Nombre de la empresa o razón social:</label>
                    <input type="text" class="form-control item" id="nombreEmpresa" name="nombreEmpresa" placeholder="Ingrese el nombre de la empresa..." value="'.htmlspecialchars($row['nombreEmpresa'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="nombreGerente" class="form-label">Nombre del gerente o responsable:</label>
                    <input type="text" class="form-control item" id="nombreGerente" name="nombreGerente" placeholder="Ingrese el nombre del responsable de la empresa..." value="'.htmlspecialchars($row['nombreGerente'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="domicilioEmpresa" class="form-label">Domicilio del propietario o la empresa:</label>
                    <input type="text" class="form-control item" id="domicilioEmpresa" name="domicilioEmpresa" placeholder="Ingrese el domicilio de la empresa..." value="'.htmlspecialchars($row['domicilioEmpresa'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="telefonoEmpresa" class="form-label">Teléfono del propietario o la empresa:</label>
                    <input type="number" class="form-control item" id="telefonoEmpresa" name="telefonoEmpresa" placeholder="Ingrese el número..." value="'.htmlspecialchars($row['telefonoEmpresa'], ENT_QUOTES, 'UTF-8').'" required>
                    <p style="margin-left: 2px;">Nota: El telefono no debe contener ceros delante, tampoco guiones. Dígitos minimos: 10. Dígitos máximos permitidos: 20.</p>    
                </div>

                <div class="form-group">
                    <label for="domicilioGerente" class="form-label">Domicilio del gerente o responsable:</label>
                    <input type="text" class="form-control item" id="domicilioGerente" name="domicilioGerente" placeholder="Ingrese el domicilio del responsable..." value="'.htmlspecialchars($row['domicilioGerente'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono:</label>
                    <input type="number" class="form-control item" id="telefono" name="telefono" placeholder="Ingrese el número..." value="'.htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="telefonoMovil" class="form-label">Teléfono móvil:</label>
                    <input type="number" class="form-control item" id="telefonoMovil" name="telefonoMovil" placeholder="Ingrese el número..." value="'.htmlspecialchars($row['telefonoMovil'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="titularidadVehiculo" class="form-label">Titularidad del vehículo:</label>
                    <input type="text" class="form-control item" id="titularidadVehiculo" name="titularidadVehiculo" placeholder="Ingrese la titularidad del vehículo..." value="'.htmlspecialchars($row['titularidadVehiculo'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="aseguradora" class="form-label">Compañía aseguradora:</label>
                    <input type="text" class="form-control item" id="aseguradora" name="aseguradora" placeholder="Ingrese la compañía aseguradora..." value="'.htmlspecialchars($row['aseguradora'], ENT_QUOTES, 'UTF-8').'" required>
                </div>

                <div class="form-group">
                    <label for="pdfFile" class="form-label">Adjunte fotos de la documentación (archivo PDF):</label>
                    <input type="file" class="form-control item" name="pdfFile" id="pdfFile" accept="application/pdf" required>
                </div>
            ';

            if ($existingPdfPath && file_exists($existingPdfPath)) {
                echo '
                    <div class="form-group">
                        <label for="existingPdf" class="form-label">Documento PDF existente:</label>
                        <p><a class="form-control item" id="existingPdf" name="existingPdf" style="text-decoration: none;" href="'.$existingPdfPath.'" target="_blank">Ver documento actual</a></p>
                    </div>
                ';
            }

            echo '
                <div class="form-group">
                    <label for="cantidadVehiculos" class="form-label">Seleccione la cantidad de vehiculos:</label>
                    <select id="cantidadVehiculos" class="form-control item"">
                        <option value="0" disabled selected>Seleccione la cantidad...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>

                <div id="vehiculosContainer"></div>

                <div class="form-group">
                    <label for="cantidadConductores" class="form-label">Seleccione la cantidad de conductores:</label>
                    <select id="cantidadConductores" class="form-control item"">
                        <option value="0" disabled selected>Seleccione la cantidad...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>

                <div id="conductoresContainer"></div>

                <input type="hidden" id="numeroRegistroArray" name="numeroRegistroArray">
                <input type="hidden" id="fechaHabilitacionArray" name="fechaHabilitacionArray">
                <input type="hidden" id="tipoHabilitacionArray" name="tipoHabilitacionArray">
                <input type="hidden" id="cantidadAsientosArray" name="cantidadAsientosArray">
                <input type="hidden" id="vigenciaVTVArray" name="vigenciaVTVArray">
                <input type="hidden" id="polizaArray" name="polizaArray">
                <input type="hidden" id="tipoSeguroArray" name="tipoSeguroArray">
                
                <input type="hidden" id="nombresConductoresArray" name="nombresConductoresArray">
                <input type="hidden" id="dnisConductoresArray" name="dnisConductoresArray">
                <input type="hidden" id="carnetConductoresArray" name="carnetConductoresArray">
                <input type="hidden" id="vigenciaConductoresArray" name="vigenciaConductoresArray">
            ';
        } else {
            error_log('Error al ejecutar la consulta: ' . $stmt->error);
            die('Error interno del servidor.');
        }

        $stmt->close();
        $conexion->close();
    } else {
        die('Error: idSalida no está definido.');
    }
?>
