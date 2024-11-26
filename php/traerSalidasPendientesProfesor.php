<?php
    $sqlSalidasPendientes = "
        SELECT `idAnexoIV`, `estado`, `denominacionProyecto`, `dniEncargado`, `cargo`, `fechaModificacion` 
        FROM `anexoiv` 
        WHERE dniEncargado = $dni AND (estado = 1 OR estado = 4)";

    $resultSalida = $conexion->query($sqlSalidasPendientes);

    if ($resultSalida === false) {
        echo "Error in query: " . $conexion->error;
    } elseif ($resultSalida->num_rows > 0) {
        while ($salida = $resultSalida->fetch_assoc()) {
            echo '<li class="salida-item d-flex align-items-center mb-3">';
            
            if ($salida['estado'] == 4) {
                echo '<a href="#" class="btn form-control botones w-100 me-2" style="pointer-events: none; opacity: 0.5;">' . 
                    $salida['denominacionProyecto'] . ' // (SALIDA EN PROCESO DE APROBACIÓN)</a>';
            } else {
                echo '<a href="../../php/traerDatosSalida.php?idSalida=' . $salida['idAnexoIV'] . '" class="btn form-control botones w-100 me-2">' . 
                    $salida['denominacionProyecto'] . 
                '</a>';
                // Botones para enviar y cancelar
                echo '<div class="botonera">
                        <button class="btn btn-success" onclick="enviarSalida(' . $salida['idAnexoIV'] . ')">Enviar</button>
                        <button class="btn btn-danger" onclick="cancelarSalida(' . $salida['idAnexoIV'] . ')">Cancelar</button>
                        <button class="btn btn-primary" onclick="infoSalida(' . $salida['idAnexoIV'] . ')">Info (?)</button>
                    </div>';
            }
            
            echo '</li>                    <hr>'; // Cerrar la lista
            
        }
    } else {
        echo "No hay salidas pendientes.";
    }

    $resultSalida->close();
    $conexion->close();
?>
