<?php
    include '../../php/verificarSessionEstudiantes.php';

    $sqlAnexoV = '
        SELECT aiv.idAnexoIV, aiv.denominacionProyecto, aiv.distanciaSalida
        FROM anexoiv aiv
        JOIN anexov av ON av.fkAnexoIV = aiv.idAnexoIV
        WHERE av.dni = '.$dniEstudiante."
        AND aiv.estado = 3
        AND aiv.fechaSalida > CURDATE()
        ";

    $resultado = mysqli_query($conexion, $sqlAnexoV);
    if (!$resultado) {
        echo "Error en la consulta: " . mysqli_error($conexion);
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menú de Salidas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/hijosMenu.css">
    </head>
    <body>
        <nav class="navbar navbar-custom">
            <div class="container-fluid d-flex align-items-center">
                <a onclick="window.history.back();" class="btn btn-warning ms-auto"  style="color: white; font-family: system-ui;">Atrás</a>
            </div>
        </nav>
        <div class="container">
            <h1>Salidas Educativas</h1>
            <div class="row mt-5">
                <div class="col-md">
                    <h3>Salidas Activas (Aprobadas)</h3>
                    <hr>
                    <ul>
                        <?php
                            if (mysqli_num_rows($resultado) > 0) {
                                while ($filaAnexoIV = mysqli_fetch_assoc($resultado)) {
                                    if ($filaAnexoIV['distanciaSalida'] == 1 || $filaAnexoIV['distanciaSalida'] == 2){
                                        echo "<li><a class='btn border-bottom border-top form-control disabled' tabindex='-1' style='pointer-events: none;'>" . $filaAnexoIV['denominacionProyecto'] . " (Salida de menos de 24 hs)</a></li>";
                                    }
                                    else{
                                        echo "<li><a href='menuSalida.php?idSalida=" . $filaAnexoIV['idAnexoIV'] . "' class='btn border-bottom border-top form-control'>" . $filaAnexoIV['denominacionProyecto'] . "</a></li>";
                                    }
                                }
                            } else {
                                echo "<li>No hay salidas educativas activas.</li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>