<?php
    include '../../php/verificarSessionProfesores.php';

    $idSalida = $_SESSION['idSalida'];
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

    // Consulta para obtener el estado de la salida
    $query = "SELECT estado FROM anexoiv WHERE idAnexoIV = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idSalida);
    $stmt->execute();
    $result = $stmt->get_result();

    $estadoSalida = null;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $estadoSalida = $row['estado']; // Asumiendo que 'estado' es el nombre de la columna
    }
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu de Salidas</title>
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
                    echo $_SESSION['denominacionProyecto'];
                ?>
            </h1>
            <div class="row mt-5">
                <?php if ($estadoSalida != 3): // Mostrar formularios si el estado no es 3 ?>
                    <div class="col-md-6">
                        <h3>Formularios</h3>
                        <hr>
                        <ul>
                            <li><a href="formularioAnexos.php" class="btn form-control botones w-100 mb-3">Anexo V / Anexo VIII / Planilla Informativa</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="<?php echo ($estadoSalida == 3) ? 'col-md-12' : 'col-md-6'; // Ocupa el 100% si el estado es 3 ?>">
                    <h3>Documentos (PDF)</h3>
                    <hr>
                    <ul>
                        <?php include('../../php/menuSalidaTraerPDF.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
