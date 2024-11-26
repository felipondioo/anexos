<?php
    session_start();
    include('conexion.php');
    var_dump($_SESSION);

    if (isset($_SESSION['dniDirector'])) {
        $dniDirector = $_SESSION['dniDirector'];
        $sql_director = "SELECT * FROM personal WHERE dni = '$dniDirector'";
        $result_director = $conexion->query($sql_director);

        if ($result_director -> num_rows > 0) {
            $row = $result_director->fetch_assoc();
            $_SESSION['nombreDir'] = $row['nombre'];
            $_SESSION['apellidoDir'] = $row['apellido'];

            $hijoSQL = 'SELECT `dni_alumnos` FROM `padresalumnos` WHERE `dni_padrestutores` = "' . $dniDirector . '"';
            include('datosHijo.php');
        } else {
            header('Location: error.php');
            exit;
        }

        $conexion->close();        
        header("Location: ../indexs/director/directivo.php");
        exit;
    }

    elseif (isset($_SESSION['dniProfesor'], $_SESSION['dniPadre'])) {
        $dniProfesor = $_SESSION['dniProfesor'];
        $sql_profesor = "SELECT * FROM personal WHERE dni = '$dniProfesor'";
        $result_profesor = $conexion->query($sql_profesor);

        if ($result_profesor->num_rows > 0) {
            $row = $result_profesor->fetch_assoc();
            $_SESSION['nombreDoc'] = $row['nombre'];
            $_SESSION['apellidoDoc'] = $row['apellido'];
            
            $_SESSION['nombrePadre'] = $row['nombre'];
            $_SESSION['apellidoPadre'] = $row['apellido'];

            $hijoSQL = 'SELECT `dni_alumnos` FROM `padresalumnos` WHERE `dni_padrestutores` = "'.$dniProfesor.'"';
            include('datosHijo.php');
        } else {
            header('Location: error.php');
            exit;
        }

        $conexion->close();
        header("Location: ../indexs/profesores/profesores.php");
        exit;
    }

    elseif (isset($_SESSION['dniProfesor'])) {
        $dniProfesor = $_SESSION['dniProfesor'];
        $sql_profesor = "SELECT * FROM personal WHERE dni = '$dniProfesor'";
        $result_profesor = $conexion->query($sql_profesor);

        if ($result_profesor->num_rows > 0) {
            $row = $result_profesor->fetch_assoc();
            $_SESSION['nombreDoc'] = $row['nombre'];
            $_SESSION['apellidoDoc'] = $row['apellido'];

            $hijoSQL = 'SELECT `dni_alumnos` FROM `padresalumnos` WHERE `dni_padrestutores` = "'.$dniProfesor.'"';
            include('datosHijo.php');
        } else {
            header('Location: error.php');
            exit;
        }

        $conexion->close();
        header("Location: ../indexs/profesores/profesores.php");
        exit;
    }

    elseif (isset($_SESSION['dniPadre'])) {
        $dniPadre = $_SESSION['dniPadre'];
        $sql_padre = "SELECT * FROM padrestutores WHERE dni = '$dniPadre'";
        $result_padre = $conexion->query($sql_padre);

        if ($result_padre->num_rows > 0) {
            $row = $result_padre->fetch_assoc();
            $_SESSION['nombrePadre'] = $row['nombre'];
            $_SESSION['apellidoPadre'] = $row['apellido'];

            $hijoSQL = 'SELECT `dni_alumnos` FROM `padresalumnos` WHERE `dni_padrestutores` = "'.$dniPadre.'"';
            include('datosHijo.php');
        } else {
            header('Location: error.php');
            exit;
        }

        $conexion->close();
        header("Location: ../indexs/padres/padres.php");
        exit;
    }

    elseif (isset($_SESSION['dniEstudiante'])) {
        $dniEstudiante = $_SESSION['dniEstudiante'];
        $sql_estudiante = "SELECT * FROM alumnos WHERE dni = '$dniEstudiante'";
        $result_estudiante = $conexion->query($sql_estudiante);

        if ($result_estudiante->num_rows > 0) {
            $row = $result_estudiante->fetch_assoc();
            $_SESSION['nombreEstudiante'] = $row['nombre'];
            $_SESSION['apellidoEstudiante'] = $row['apellido'];
            
            // Convertir fechan (fecha de nacimiento) a un objeto DateTime
            $fechaNacimiento = new DateTime($row['fechan']);
            $fechaActual = new DateTime(); // Fecha actual
            
            // Calcular la diferencia en años
            $edad = $fechaNacimiento->diff($fechaActual)->y;
            
            // Guardar la edad en la sesión
            $_SESSION['edad'] = $edad;
            

        } else {
            header('Location: error.php');
            exit;
        }

        $conexion->close();
        header("Location: ../indexs/estudiantes/estudiantes.php");
        exit;
    }

    else {
        header('Location: ../index.php');
        exit;
    }
?>
