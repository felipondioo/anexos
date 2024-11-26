<?php
    $sqlSalidasAprobadas = "SELECT `idAnexoIV`, `estado`, `denominacionProyecto`, `distanciaSalida`, `dniEncargado`, `cargo`, `fechaModificacion` FROM `anexoiv` WHERE dniEncargado = $dni AND estado = 3 AND fechaSalida > CURDATE()";
    $resultSalidaAprobada = $conexion->query($sqlSalidasAprobadas);

    if ($resultSalidaAprobada === false) {
        echo "Error in query: " . $conexion->error;
    } elseif ($resultSalidaAprobada->num_rows > 0) {
        while ($salidaA = $resultSalidaAprobada->fetch_assoc()) {
            echo '<li>
                    <a href="../../php/traerDatosSalida.php?idSalida=' . $salidaA['idAnexoIV'] . '" class="btn form-control botones w-100 mb-3" >' . 
                       $salidaA['denominacionProyecto'] . 
                    '</a>
                  </li>';
        }
    } else {
        echo "No hay salidas aprobadas activas.";
    }

    $resultSalidaAprobada->close();
?>
