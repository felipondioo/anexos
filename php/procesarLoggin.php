<?php
    session_start();
    session_unset();

    include('conexion.php');
    include('passwordDirector.php');

    $dni = mysqli_real_escape_string($conexion, $_POST["dni"]);
    $password = mysqli_real_escape_string($conexion, $_POST["password"]);

    // Verificar si es el director
    if ($dni == $dniDirector && $password == $password_d) {
        $_SESSION['dniDirector'] = $dniDirector;
        header("Location: datosSesion.php");
        exit;
    }

    // Consulta en la tabla 'personal'
    $sql_personal = "SELECT * FROM personal WHERE dni = '$dni'";
    $result_personal = $conexion->query($sql_personal);

    if ($result_personal->num_rows > 0) {
        $row_personal = $result_personal->fetch_assoc();
        
        // Verificar contraseña del personal
        if ($password === $row_personal["pass"]) {
            $_SESSION['dniProfesor'] = $dni;

            // Consulta en la tabla 'padrestutores' si es que hay un registro en 'personal'
            $sql_ProfesorPadre = "SELECT `dni`, `nombre`, `apellido` FROM padrestutores WHERE dni = '$dni'";
            $result_ProfesorPadre = $conexion->query($sql_ProfesorPadre);

            if ($result_ProfesorPadre && $result_ProfesorPadre->num_rows > 0) {
                // Si también está en la tabla 'padrestutores', asignamos dniPadre
                $_SESSION['dniPadre'] = $dni;
                mysqli_free_result($result_ProfesorPadre); // Liberar el resultado de la consulta de padrestutores
            }

            // Liberar el resultado de la consulta de personal
            mysqli_free_result($result_personal);
            
            // Redirigir a la página de datos de sesión
            header("Location: datosSesion.php");
            exit;
        }
    }

    // Consulta independiente en la tabla 'padrestutores' si no hay resultado en 'personal'
    $sql_padre = "SELECT * FROM padrestutores WHERE dni = '$dni'";
    $result_padre = $conexion->query($sql_padre);

    if ($result_padre->num_rows > 0) {
        $row_padre = $result_padre->fetch_assoc();
        
        // Verificar contraseña del padre/tutor
        if ($password === $row_padre["contrasena"]) {
            $_SESSION['dniPadre'] = $dni;
            
            // Liberar el resultado de la consulta de padrestutores
            mysqli_free_result($result_padre);

            // Redirigir a la página de datos de sesión
            header("Location: datosSesion.php");
            exit;
        }
    }

    // Consulta independiente en la tabla 'alumnos' si no hay resultado en 'padrestutores'
    $sql_alumno = "SELECT * FROM alumnos WHERE dni = '$dni'";
    $result_alumno = $conexion->query($sql_alumno);

    if ($result_alumno->num_rows > 0) {
        $row_alumno = $result_alumno->fetch_assoc();
        
        // Verificar contraseña del alumno
        if ($password === $row_alumno["clave"]) {
            $_SESSION['dniEstudiante'] = $dni;
            
            // Liberar el resultado de la consulta de alumnos
            mysqli_free_result($result_alumno);

            // Redirigir a la página de datos de sesión
            header("Location: datosSesion.php");
            exit;
        }
    }

    // Liberar los resultados si aún no se han liberado
    if ($result_personal) mysqli_free_result($result_personal);
    if ($result_padre) mysqli_free_result($result_padre);
    if ($result_alumno) mysqli_free_result($result_alumno);

    // Cerrar la conexión y redirigir a la página de error
    $conexion->close();
    header("Location: error.php");
    exit;
?>
