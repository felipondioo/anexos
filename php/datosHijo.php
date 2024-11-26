<?php
    $resultadoHijos = mysqli_query($conexion, $hijoSQL);

    if ($resultadoHijos) {
        $hijos = [];
        while ($filaHijo = mysqli_fetch_assoc($resultadoHijos)) {
            $dniAlumno = $filaHijo['dni_alumnos'];
            echo $dniAlumno;
            $alumnoSQL = "SELECT * FROM `alumnos` WHERE dni = '$dniAlumno'";
            $resultadoAlumno = mysqli_query($conexion, $alumnoSQL);

            if ($resultadoAlumno) {
                while ($datosAlumno = mysqli_fetch_assoc($resultadoAlumno)) {
                    $hijos[] = $datosAlumno;
                }
            }
        }
        $_SESSION['hijos'] = $hijos; // Almacena los datos de los hijos en la sesión
    } else {
        $_SESSION['error'] = "Error al obtener DNIs de los alumnos asociados al padre";
    }
?>