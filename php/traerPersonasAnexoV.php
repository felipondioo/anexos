<?php
    session_start();
    include 'verificarSessionNoStart.php';

    include 'conexion.php';

    $sql = "SELECT * FROM `anexov` WHERE fkAnexoIV = ".$_SESSION['idSalida']." ORDER BY dni";

    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        die('Ocurrió un error: ' . mysqli_error($conexion));
    }

    $pasajeros = array();

    if (mysqli_num_rows($result) > 0) {
        while($fila = mysqli_fetch_assoc($result)) {
            $datos = array(
                'dni' => $fila['dni'],
                'apellidoNombre' => $fila['apellidoNombre'],
                'edad' => $fila['edad'],
                'cargo' => $fila['cargo']
            );
            $pasajeros[] = $datos;
        }
    }

    echo json_encode($pasajeros);

    mysqli_free_result($result);
    mysqli_close($conexion);
?>