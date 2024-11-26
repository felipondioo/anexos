<?php
    header('Access-Control-Allow-Origin: *'); // Allows all origins, or replace * with your specific domain if needed
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Specifies allowed methods
    header('Access-Control-Allow-Headers: Content-Type'); // Allows specific headers

    session_start();
    include 'verificarSessionNoStart.php';

    $sql = "SELECT * FROM `anexoviii` WHERE fkAnexoIV = ".$_SESSION['idSalida']."";
    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        die('Ocurrió un error: ' . mysqli_error($conexion));
    }

    $listado = [];

    if (mysqli_num_rows($result) > 0) {
        while($fila = mysqli_fetch_assoc($result)) {
            $datos = array(
                'nroRegistro' => $fila['nroRegistro'],
                'fechaHabilitacion' => $fila['fechaHabilitacion'],
                'tipoHabilitacion' => $fila['tipoHabilitacion'],
                'cantAsientos' => $fila['cantAsientos'],
                'vigenciaVTV' => $fila['vigenciaVTV'],
                'nroPoliza' => $fila['nroPoliza'],
                'tipoSeguro' => $fila['tipoSeguro'],
                'nombreConductor' => $fila['nombreConductor'],
                'dniConductor' => $fila['dniConductor'],
                'carnetConducir' => $fila['carnetConducir'],
                'vigenciaConductor' => $fila['vigenciaConductor']
            );
            $listado[] = $datos;
        }
        echo json_encode(['status' => 'registrosSI', 'data' => $listado]);
    }
    else {
        echo json_encode(['status' => 'registrosNO', 'data' => []]);
    }

    mysqli_free_result($result);
    mysqli_close($conexion);
?>
