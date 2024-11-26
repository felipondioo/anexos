<?php
    include '../../php/verificarSessionDirector.php';

    $hijos = isset($_SESSION['hijos']) ? $_SESSION['hijos'] : [];
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="../../librerias/jquery.js?v=1"></script>
        <link rel="stylesheet" href="../../librerias/bootstrap.css">
        <link rel="stylesheet" href="../../css/estilos.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Menu</title>
    </head>
    <body>

        <a href="../../php/logout.php" class="btn btn-danger">Cerrar sesión</a>
        <div class="row justify-content-center">
            <div class="col-6">
                <h2 class="col-12 text-center mt-4">Opciones</h2>
                <div class="col-12 text-center mt-4">
                    <a href="adminSalidas.php" class="btn border-bottom border-top form-control" style="width: 100%;">Administrar Salidas Educativas</a>
                </div>
            </div>
            <div class="col-6">
                <h2 class="col-12 text-center mt-4">Hijos a Cargo</h2>
                <div class="col-12 text-center mt-4">
                    <?php
                        include('../../php/conexion.php');

                        $dniPadre = $_SESSION['dniDirector'];

                        $hijoSQL = "SELECT `dni_alumnos` FROM `padresalumnos` WHERE dni_padrestutores = '$dniPadre'";

                        $resultadoHijos = mysqli_query($conexion, $hijoSQL);
                        if ($resultadoHijos) {
                            while ($filaHijo = mysqli_fetch_assoc($resultadoHijos)) {

                                $dniAlumno = $filaHijo['dni_alumnos'];

                                $alumnoSQL = "SELECT `nombre`, `apellido` FROM `alumnos` WHERE dni = '$dniAlumno'";
                                $resultadoAlumno = mysqli_query($conexion, $alumnoSQL);

                                if ($resultadoAlumno) {
                                    while ($datosAlumno = mysqli_fetch_assoc($resultadoAlumno)) {
                                        $nombreAlumno = $datosAlumno['nombre'];
                                        $apellidoAlumno = $datosAlumno['apellido'];

                                        echo '<a href="../../php/traerDatosHijoSalida.php?dniAlumno='.$dniAlumno.'" class="btn border-bottom border-top form-control" style="width: 100%;">'.$apellidoAlumno.' '.$nombreAlumno.'</a><br><br>';
                                    }
                                } else {
                                    echo "Error al obtener datos del alumno";
                                }
                            }
                        } else {
                            echo "Error al obtener DNIs de los alumnos asociados al padre";
                        }
                    ?>
                </div>
            </div>
        </div>
        <script src="../../librerias/jquery.js"></script>
        <script src="../../librerias/boostrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
                    