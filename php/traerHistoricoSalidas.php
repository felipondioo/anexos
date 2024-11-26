<?php 
    include 'verificarSessionNoStart.php';
    include 'conexion.php';
    $sql = "SELECT * FROM `anexoiv` WHERE estado NOT IN (1, 4) and dniEncargado = $dni";
    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        die('Ocurrió un error: ' . mysqli_error($conexion));
    }

    if (mysqli_num_rows($result) > 0) {
        $historico = true;
        while($fila = mysqli_fetch_assoc($result)) {
            //Tipo
            if ($fila['tipoSolicitud'] == 1) {
                $tipo = "Representación Institucional";
            } else if ($fila['tipoSolicitud'] == 2) {
                $tipo = "Educativa";
            } else {
                $tipo = "Desconocido";
            }

            // Estado
            if ($fila['estado'] == 0) {
                $estado = "Rechazada";
            } else if ($fila['estado'] == 2) {
                $estado = "Cancelada (Archivada)";
            } else if ($fila['estado'] == 3) {
                $estado = "Aprobada";
            } else {
                $estado = "Desconocido";
            }

            // Distancia y duración
            switch ($fila['distanciaSalida']) {
                case 1: 
                case 2: 
                    $duracion = "Menos";
                    break;
                case 3: 
                case 4: 
                case 5: 
                    $duracion = "Más";
                    break;
                default: 
                    $duracion = "No especificada";
                    break;
            }

            $anexoviiiHabil = ($fila['anexoviiiHabil'] == 1) ? "Si" : "No";

            // Formatear fechas en dd/mm/aa
            $fechaSalida = date("d/m/y", strtotime($fila['fechaSalida']));
            $fechaRegreso = date("d/m/y", strtotime($fila['fechaRegreso']));
            $fechaLimite = date("d/m/y", strtotime($fila['fechaLimite']));
            $fechaModificacion = date("d/m/y", strtotime($fila['fechaModificacion']));

            echo '<tr>
                    <td> '.$fila['idAnexoIV'].' </td>
                    <td> '.$tipo.' </td>
                    <td> '.$fila['denominacionProyecto'].' </td>
                    <td> '.$estado.'</td>
                    <td> '.$duracion.' de 24hs </td>
                    <td> '.$fila['lugarVisita'].' </td>
                    <td> '.$fechaSalida.' </td>
                    <td> '.$fechaRegreso.' </td>
                    <td> '.$anexoviiiHabil.'</td>
                    <td> '.$fechaLimite.' </td>
                    <td> '.$fechaModificacion.' </td>
                </tr>';
        }
    }
    else{
        $historico = false;
    }

    mysqli_free_result($result);
    mysqli_close($conexion);
?>
