<?php
    include '../../php/verificarSessionEstudiantes.php';
    $dniAlumno = $_SESSION['dniEstudiante'];
    $_SESSION['idSalida'] = $_GET['idSalida'];
    $idSalida = $_SESSION['idSalida'];

    $sql = "SELECT * FROM anexoiv WHERE idAnexoIV = $idSalida";
    $resultado = mysqli_query($conexion, $sql);

    $fila = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Salida Educativa: <?php echo $_SESSION['denominacionProyecto']; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/menuSalida.css">
    </head>
    <body>
        <nav class="navbar navbar-custom">
            <div class="container-fluid d-flex align-items-center">
                <a onclick="window.history.back();" class="btn btn-warning ms-auto" style="color: white;">Atrás</a>
            </div>
        </nav>

        <div class="container">
            <h1>
                <?php
                    echo $fila['denominacionProyecto'];
                ?>
            </h1>
            <div class="row mt-5">
                <div class="col-md-6">
                    <h3>Formularios</h3>
                    <hr>
                    <ul>
                        <li><a href="formularioAnexoVII.php" class="btn form-control botones w-100 mb-3">Anexo VII</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3>Documentos (PDF)</h3>
                    <hr>
                    <ul>
                        <?php
                            if (isset($idSalida) && isset($dniAlumno)) {
                            $sqlAnexoVI = "SELECT fkAnexoIV FROM anexovii WHERE fkAnexoIV = ? AND dniEstudiante = ?";
                            $stmtAnexoVI = $conexion->prepare($sqlAnexoVI);
                            $stmtAnexoVI->bind_param('is', $idSalida, $dniAlumno); 
                            $stmtAnexoVI->execute();
                            $stmtAnexoVI = $stmtAnexoVI->get_result();
                            
                            if ($stmtAnexoVI->num_rows > 0) {
                                echo '<li><a href="../pdf/plantillaAnexoVII.php" class="btn form-control botones w-100 mb-3">Anexo VII</a></li>';
                            } else {
                                echo '<li><a class="btn form-control botones w-100 mb-3" disabled>Anexo VII (Sin completar)</a></li>';
                            }
                            $stmtAnexoVI->close();
                            $conexion->close();
                            } else {
                                die('Error: idSalida o dniAlumno no están definidos.');
                            } 
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
