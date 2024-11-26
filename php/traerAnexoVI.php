<?php
    session_start();
    include('conexion.php');
    $idSalida = $_SESSION['idSalida'];
    $dniAlumno = $_SESSION['dniAlumno'];

    $sql = "SELECT * FROM anexovi WHERE fkAnexoIV = '$idSalida' AND dniAlumno = '$dniAlumno'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);

        // 1 para "Sí", 0 para "No"
        $obraSocialCheckedSi = $fila['obraSocial'] ? 'checked' : '';
        $obraSocialCheckedNo = !$fila['obraSocial'] ? 'checked' : '';
        
        // Mostrar campos
        $displayObraInputs = $fila['obraSocial'] ? 'block' : 'none';

        // Verificar si constanciaMedica, nombreObra o nSocio contienen "-" y asignar un valor vacío si es el caso
        $constanciaMedica = $fila['constanciaMedica'] === '-' ? '' : $fila['constanciaMedica'];
        $nombreObra = $fila['nombreObra'] === '-' ? '' : $fila['nombreObra'];
        $nSocio = $fila['nSocio'] === '-' ? '' : $fila['nSocio'];

        echo '
            <div class="form-group">
                <label class="form-label labels6" for="constancia">Constancia médica (Opcional):</label>
                <textarea class="form-control item" id="constancia" name="constancia" rows="3" placeholder="Descripción...">' . htmlspecialchars($constanciaMedica) . '</textarea>
            </div>

            <div class="wrapper">
                <div class="title">¿Tiene Obra Social / Prepaga?</div>
                <div class="box">
                    <input type="radio" id="obraSi" ' . $obraSocialCheckedSi . ' name="obraSocial" value="1" onclick="toggleObraSocialInput(true)">
                    <input type="radio" id="obraNo" ' . $obraSocialCheckedNo . ' name="obraSocial" value="0" onclick="toggleObraSocialInput(false)">

                    <label for="obraSi" class="obraSi">
                        <div class="dot"></div>
                        <div class="text">Sí</div>
                    </label>

                    <label for="obraNo" class="obraNo">
                        <div class="dot"></div>
                        <div class="text">No</div>
                    </label>
                    
                    <br>
                    <div id="nomInput" style="display:' . $displayObraInputs . ';">
                        <p class="form-label" for="nomObra">Nombre de la Obra Social / Prepaga:</p>
                        <input type="text" class="form-control item" id="nomObra" name="nomObra" value="' . htmlspecialchars($nombreObra) . '" placeholder="Ingrese el nombre...">
                    </div>

                    <div id="nroInput" style="display:' . $displayObraInputs . ';">
                        <p class="form-label" for="nroObra">Número de Socio:</p>
                        <input type="text" class="form-control item" id="nroObra" name="nroObra" value="' . htmlspecialchars($nSocio) . '" placeholder="Ingrese el número...">
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
                    
                    <div id="listaTelefonos" class="listaTelefonos">';
                        $telefonos = !empty($fila['telefonos']) ? explode(',', $fila['telefonos']) : [];
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

                    <input type="hidden" id="telefonosOculto" name="telefonos" value="' . htmlspecialchars($fila['telefonos']) . '">
                </div>
            </div>
            <br><br>
        ';
    } else {
        echo '
            <div class="form-group">
                <label class="form-label labels6" for="constancia">Constancia médica (Opcional):</label>
                <textarea class="form-control item" id="constancia" name="constancia" rows="3" placeholder="Descripción..."></textarea>
            </div>

            <div class="wrapper">
                <div class="title">¿Tiene Obra Social / Prepaga?</div>
                <div class="box">
                    <input type="radio" id="obraSi" name="obraSocial" value="1" onclick="toggleObraSocialInput(true)">
                    <input type="radio" id="obraNo" name="obraSocial" value="0" onclick="toggleObraSocialInput(false)">

                    <label for="obraSi" class="obraSi">
                        <div class="dot"></div>
                        <div class="text">Sí</div>
                    </label>

                    <label for="obraNo" class="obraNo">
                        <div class="dot"></div>
                        <div class="text">No</div>
                    </label>
                </div>
            </div>

            <br>
            <div id="nomInput" style="display:none;">
                <label class="form-label" for="nomObra">Nombre de la Obra Social / Prepaga:</label>
                <input type="text" class="form-control item" id="nomObra" name="nomObra" placeholder="Ingrese el nombre...">
            </div>

            <div id="nroInput" style="display:none;">
                <label class="form-label" for="nroObra">Número de socio:</label>
                <input type="text" class="form-control item" id="nroObra" name="nroObra" placeholder="Ingrese el número...">
            </div>

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
            <br><br>
        ';
    }
    mysqli_close($conexion);
?>
