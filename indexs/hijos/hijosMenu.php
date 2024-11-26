<?php
    session_start();

    if (isset($_SESSION['dniProfesor'])) {
        $dniPadre = $_SESSION['dniProfesor'];
    } elseif (isset($_SESSION['dniDirector'])) {
        $dniPadre = $_SESSION['dniDirector'];
    } elseif (isset($_SESSION['dniPadre'])) {
        $dniPadre = $_SESSION['dniPadre'];
    } else {
        header('Location: ../../index.php');
        exit;
    }

    include('../../php/conexion.php');

    $dniHijo = $_SESSION['dniAlumno'];

    $sqlAnexoV = "
        SELECT aiv.idAnexoIV, aiv.denominacionProyecto, aiv.distanciaSalida
        FROM anexoiv aiv
        JOIN anexov av ON av.fkAnexoIV = aiv.idAnexoIV
        WHERE av.dni = '$dniHijo' 
        AND aiv.estado = 3
        AND aiv.fechaSalida > CURDATE()"; // Filtra solo las salidas programadas para fechas futuras

    $resultado = mysqli_query($conexion, $sqlAnexoV);
    if (!$resultado) {
        echo "Error en la consulta: " . mysqli_error($conexion);
        exit;
    }

    if (empty($dniPadre)) {
        header('Location: ../index.php');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu de Salidas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/hijosMenu.css">
    </head>
    <body>
        <nav class="navbar navbar-custom">
            <div class="container-fluid d-flex align-items-center">
                <a onclick="window.history.back();" class="btn btn-warning ms-auto" style="color: white;">Atrás</a>
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
                                        echo "<li><a href='../../php/datosSalidaHijo.php?idSalida=" . $filaAnexoIV['idAnexoIV'] . "' class='btn border-bottom border-top form-control'>" . $filaAnexoIV['denominacionProyecto'] . "</a></li>";
                                    }
                                }
                            } else {
                                echo "<li>No hay salidas educativas activas para este alumno.</li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
