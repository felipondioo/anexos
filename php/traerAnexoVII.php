<?php
    include('conexion.php');

    // Obtener el ID de la salida desde la sesión
    $idSalida = $_SESSION['idSalida'];
    $dniEstudiante = $_SESSION['dniEstudiante'];

    // Consultar los datos del Anexo VII basados en el idSalida y dniEstudiante
    $sql = "SELECT * FROM anexovii WHERE fkAnexoIV = '$idSalida' AND dniEstudiante = '$dniEstudiante'";
    $resultado = mysqli_query($conexion, $sql);

    // Si hay resultados, asignar los valores a las variables
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);

        // Si los campos contienen "-", se asignan como cadena vacía
        $domicilio = $fila['domicilio'];
        $altura = $fila['altura'];
        $localidad = $fila['localidad'];
        $observaciones = ($fila['observaciones'] === '-') ? '' : $fila['observaciones'];
        $obraSocial = $fila['obraSocial'];
        $nombreObraSocial = ($fila['nombreObraSocial'] === '-') ? '' : $fila['nombreObraSocial'];
        $numeroAfiliado = ($fila['numeroAfiliado'] === '-') ? '' : $fila['numeroAfiliado'];
        $telefonos = !empty($fila['telefonos']) ? explode(',', $fila['telefonos']) : [];

        // Mostrar el formulario con los valores recuperados
        echo '
        <div class="form-group">
            <div class="form-group">
                <label class="form-label labels6" for="domicilio">Domicilio:</label>
                <input type="text" class="form-control item" id="domicilio" value="'.$domicilio.'" placeholder="Ingrese su domicilio...">
            </div>
            <div class="form-group">
                <label class="form-label labels6" for="altura">Altura:</label>
                <input type="number" class="form-control item" id="altura" value="'.$altura.'" placeholder="Ingrese su número de domicilio (altura)...">
            </div>
            <div class="form-group">
                <label class="form-label labels6" for="localidad">Localidad:</label>
                <input type="text" class="form-control item" id="localidad" value="'.$localidad.'" placeholder="Ingrese su localidad de residencia...">
            </div>
        </div>

        <br>
        <div class="wrapper">
            <div class="title">Observaciones</div>
            <div class="box">
                <h6>Deje constancia de cualquier indicación que estime necesario deba conocer el personal docente a cargo y personal médico:</h6>
                <br>
                <textarea class="form-control item" id="observaciones" name="observaciones" rows="3" placeholder="Descripción...">' . $observaciones . '</textarea>
            </div>
        </div>
        
        <br>
        <div class="wrapper">
            <div class="title">¿Tiene obra social?</div>
            <div class="box">
                <input type="radio" id="obraSi" ' . ($obraSocial ? 'checked' : '') . ' name="obraSocial" value="si">
                <input type="radio" id="obraNo" ' . ($obraSocial ? '' : 'checked') . ' name="obraSocial" value="no">

                <label for="obraSi" class="obraSi">
                    <div class="dot"></div>
                    <div class="text">Sí</div>
                </label>

                <label for="obraNo" class="obraNo">
                    <div class="dot"></div>
                    <div class="text">No</div>
                </label>

                <br>
                <div id="obraSocialNombreDiv" style="display:none;">
                    <h5 style="margin-bottom: 15px;">Nombre de la Obra Social / Prepaga:</h5>
                    <input type="text" class="form-control item" id="nombreObraSocial" value="' . $nombreObraSocial . '" name="nombreObraSocial" placeholder="Ingrese el nombre...">
                </div>

                <div id="obraSocialNumeroDiv" style="display:none;">
                    <h5 style="margin-bottom: 15px;">Número de afiliado (Número de Socio):</h5>
                    <input type="text" class="form-control item" id="numeroAfiliado" value="' . $numeroAfiliado . '" name="numeroAfiliado" placeholder="Ingrese el número...">
                </div>

                <div id="obraSocialMensaje" style="display:none;">
                    <p>En caso de respuesta positiva deberá acompañar la presente planilla con carnet o copia del carnet.</p>
                </div>
            </div>
        </div>

        <br>
        <div class="wrapper">
            <div class="title">Teléfonos de contacto:</div>
            <div class="box">
                <h6 style="margin-bottom: 10px;">(Consignar varios)</h6>
                <div id="telefonosDiv" style="display: flex; gap: 10px; align-items: center;">
                    <input class="form-control item" type="text" id="telefono" placeholder="Ingrese un número de teléfono...">
                    <button type="button" class="cargar" id="agregarTelefono">Agregar</button>
                </div>
                
                <input type="hidden" id="telefonosOculto" name="telefonos" value="'.$fila['telefonos'].'">
                <div id="listaTelefonos" class="listaTelefonos">';
                    // Mostrar la lista de teléfonos
                    foreach ($telefonos as $index => $telefono) {
                        $telefono = trim($telefono);
                        if (!empty($telefono)) {
                            echo '<div class="telefono-container" style="display: flex; gap: 10px; align-items: center;">
                                    <label class="telefono-label item" style="padding: 10px 20px;">' . htmlspecialchars($telefono) . '</label>
                                    <button type="button" class="eliminar" onclick="eliminarTelefono(' . $index . ')">Eliminar</button>
                                  </div>';
                        }
                    }
                    echo '
                </div>
            </div>
        </div>
        ';
    } else {
        echo '
        <div class="form-group">
            <div class="form-group">
                <label class="form-label labels6" for="domicilio">Domicilio:</label>
                <input type="text" class="form-control item" id="domicilio" placeholder="Ingrese su domicilio...">
            </div>
            <div class="form-group">
                <label class="form-label labels6" for="altura">Altura:</label>
                <input type="number" class="form-control item" id="altura" placeholder="Ingrese la altura de su domicilio...">
            </div>
            <div class="form-group">
                <label class="form-label labels6" for="localidad">Localidad:</label>
                <input type="text" class="form-control item" id="localidad" placeholder="Ingrese su localidad...">
            </div>
        </div>

        <br>
        <div class="wrapper">
            <div class="title">Constancia médica</div>
            <div class="box">
                <p class="form-label labels6" for="observaciones">Deje cualquier indicación que crea necesaria que deba conocer el personal a cargo y personal médico:</p>
                <textarea class="form-control item" id="observaciones" name="observaciones" rows="3" placeholder="Descripción..."></textarea>
            </div>
        </div>
        
        <br>
        <div class="wrapper">
            <div class="title">¿Tiene obra social?</div>
            <div class="box">
                <input type="radio" id="obraSi" name="obraSocial" value="si">
                <input type="radio" id="obraNo" name="obraSocial" value="no">

                    <label for="obraSi" class="obraSi">
                        <div class="dot"></div>
                        <div class="text">Sí</div>
                    </label>

                    <label for="obraNo" class="obraNo">
                        <div class="dot"></div>
                        <div class="text">No</div>
                    </label>
                <br>
                <div id="obraSocialNombreDiv" style="display:none;">
                    <h5 style="margin-bottom: 15px;">Ingrese el nombre de su obra social:</h5>
                    <input type="text" class="form-control item" id="nombreObraSocial" name="nombreObraSocial" placeholder="Ingrese el nombre...">
                </div>

                <div id="obraSocialNumeroDiv" style="display:none;">
                    <h5 style="margin-bottom: 15px;">Ingrese su número de afiliado (Número de socio):</h5>
                    <input type="text" class="form-control item" id="numeroAfiliado" name="numeroAfiliado" placeholder="Ingrese el número...">
                </div>
            </div>
        </div>

        <br>
        <div class="wrapper">
            <div class="title">Teléfonos de contacto:</div>
            <div class="box">
                <h6 style="margin-bottom: 10px;">(Consignar varios)</h6>
                <div id="telefonosDiv" style="display: flex; gap: 10px; align-items: center;">
                    <input class="form-control item" type="text" id="telefono" placeholder="Ingrese un número de teléfono...">
                    <button type="button" class="cargar" id="agregarTelefono">Agregar</button>
                </div>
                <input type="hidden" id="telefonosOculto" name="telefonos">
                <div id="listaTelefonos" class="listaTelefonos">
                </div>
            </div>
        </div>
        ';
    }
    mysqli_close($conexion);
?>
