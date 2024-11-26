<?php
    // header('Content-Type: application/json');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    include ('conexion.php');

    //Leer el cuerpo de la solicitud
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    // Si se leyó será true, sino recibe por post
    $opcion = $data['opcion'] ?? $_POST['opcion'] ?? null;

    switch($opcion){
        case 'agregarPersona':
            if (isset($_POST['idAnexoIV'], $_POST['dni'], $_POST['nombreApellido'], $_POST['fechan'], $_POST['cargo'])) {
                $idAnexoIV = $_POST['idAnexoIV'];
                $dni = $_POST['dni'];
                $nombreApellido = $_POST['nombreApellido'];
                $fechan = $_POST['fechan'];
                $fechaActual = date("Y-m-d");
                
                // Convertir las fechas en objetos DateTime
                $fechaNacimiento = new DateTime($fechan);
                $fechaHoy = new DateTime($fechaActual);
                
                // Calcular la diferencia entre las dos fechas
                $diferencia = $fechaHoy->diff($fechaNacimiento);
                
                // Obtener la edad en años
                $edad = $diferencia->y;
                $cargo = $_POST['cargo'];
                
                $sqlVerificar = "SELECT fkAnexoIV, dni FROM `anexov` WHERE dni = ? AND fkAnexoIV = ?";
                $stmtVerificar = $conexion->prepare($sqlVerificar);
                if ($stmtVerificar) {
                    $stmtVerificar->bind_param('ii', $dni, $idAnexoIV);
                    $stmtVerificar->execute();
                    $resultVerificar = $stmtVerificar->get_result();
                    if ($resultVerificar->num_rows > 0) {
                        echo json_encode(['status' => 'error', 'message' => 'La persona cargada ya está registrada en la salida']);
                    } else{

                        $sqlInsert = "INSERT INTO anexov (`fkAnexoIV`, `dni`, `apellidoNombre`, `edad`, `cargo`) VALUES (?, ?, ?, ?, ?)";

                        $stmt = $conexion->prepare($sqlInsert);

                        if ($stmt) {
                            $stmt->bind_param('iisii', $idAnexoIV, $dni, $nombreApellido, $edad, $cargo);
                            if ($stmt->execute()) {
                                
                                $sqlSelect = "SELECT dni, apellidoNombre, edad, cargo FROM anexov WHERE fkAnexoIV = ?";
                                $stmtSelect = $conexion->prepare($sqlSelect);
                                $stmtSelect->bind_param('i', $idAnexoIV);
                                $stmtSelect->execute();
                                $result = $stmtSelect->get_result();
                                $participantes = [];
                                while ($row = $result->fetch_assoc()) {
                                    $participantes[] = $row; // Guardar cada participante en el array
                                }
                                echo json_encode(['status' => 'success', 'message' => 'Registro insertado correctamente', 'participantes' => $participantes]);

                                
                                $stmtSelect->close();
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'Error al insertar el registro: ' . $stmt->error]);
                            }
                            $stmt->close();
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta: ' . $conexion->error]);
                        }                        
                    }
                    $stmtVerificar->close();
                }
                $conexion->close();
            }
        break;

        case 'agregarAcompañante':
            
            if (isset($_POST['dniAcompañante'], $_POST['nombreAcompañante'], $_POST['edadAcompañante'])) {
                session_start();
                $idAnexoIV = $_SESSION['idSalida'];
                $dni = $_POST['dniAcompañante'];
                $nombreApellido = $_POST['nombreAcompañante'];
                $edad = intval($_POST['edadAcompañante']);

                // Verificar si el acompañante ya existe en las tablas
                $sqlVerificar = "
                    SELECT 'anexov' AS source FROM `anexov` WHERE dni = ? AND fkAnexoIV = ?
                    UNION
                    SELECT 'alumnos' AS source FROM `alumnos` WHERE dni = ?
                    UNION
                    SELECT 'personal' AS source FROM `personal` WHERE dni = ?
                ";

                $stmtVerificar = $conexion->prepare($sqlVerificar);
                if ($stmtVerificar) {
                    $stmtVerificar->bind_param('iiii', $dni, $idAnexoIV, $dni, $dni);
                    $stmtVerificar->execute();
                    $resultVerificar = $stmtVerificar->get_result();

                    if ($resultVerificar->num_rows > 0) {
                        echo json_encode(['status' => 'error', 'message' => 'El acompañante ya está registrado en la salida o pertenece a la institución.']);
                    } else {
                        $sqlInsert = "INSERT INTO anexov (`fkAnexoIV`, `dni`, `apellidoNombre`, `edad`, `cargo`) VALUES (?, ?, ?, ?, ?)";
                        $stmtInsert = $conexion->prepare($sqlInsert);
                        
                        if ($stmtInsert) {
                            $cargo = 4; // Acompañante
                        
                            $stmtInsert->bind_param('iisii', $idAnexoIV, $dni, $nombreApellido, $edad, $cargo);
                        
                            if ($stmtInsert->execute()) {
                                // Obtener todos los participantes después de la inserción
                                $sqlSelect = "SELECT dni, apellidoNombre, edad, cargo FROM anexov WHERE fkAnexoIV = ?";
                                $stmtSelect = $conexion->prepare($sqlSelect);
                                $stmtSelect->bind_param('i', $idAnexoIV);
                                $stmtSelect->execute();
                                $result = $stmtSelect->get_result();
                        
                                $participantes = [];
                                while ($row = $result->fetch_assoc()) {
                                    $participantes[] = $row;
                                }
                        
                                echo json_encode(['status' => 'success', 'message' => 'Acompañante registrado correctamente', 'participantes' => $participantes]);
                                $stmtSelect->close();
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'Error al insertar el registro: ' . $stmtInsert->error]);
                            }
                            $stmtInsert->close();
                        }                        
                    }
                    $stmtVerificar->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta de verificación: ' . $conexion->error]);
                }
                $conexion->close();
            } 
        break;
        
        case 'agregarSuplente':
            
            if (isset($_POST['dniSuplente'], $_POST['nombreSuplente'], $_POST['edadSuplente'])) {
                session_start();
                $idAnexoIV = $_SESSION['idSalida'];
                $dni = $_POST['dniSuplente'];
                $nombreApellido = $_POST['nombreSuplente'];
                $edad = intval($_POST['edadSuplente']);
        
                // Verificar si el suplente existe en la tabla personal
                $sqlVerificarPersonal = "SELECT dni FROM `personal` WHERE dni = ?";
                $stmtVerificarPersonal = $conexion->prepare($sqlVerificarPersonal);
                if ($stmtVerificarPersonal) {
                    $stmtVerificarPersonal->bind_param('i', $dni);
                    $stmtVerificarPersonal->execute();
                    $resultVerificarPersonal = $stmtVerificarPersonal->get_result();
        
                    if ($resultVerificarPersonal->num_rows == 0) {
                        // Si no existe en la tabla de personal, arroja un mensaje de error
                        echo json_encode(['status' => 'error', 'message' => 'El suplente no está registrado en el personal.']);
                    } else {
                        // Verificar si el suplente ya existe en la tabla anexov para esta salida
                        $sqlVerificarAnexov = "
                            SELECT 'anexov' AS source FROM `anexov` WHERE dni = ? AND fkAnexoIV = ?
                        ";
                        $stmtVerificarAnexov = $conexion->prepare($sqlVerificarAnexov);
                        if ($stmtVerificarAnexov) {
                            $stmtVerificarAnexov->bind_param('ii', $dni, $idAnexoIV);
                            $stmtVerificarAnexov->execute();
                            $resultVerificarAnexov = $stmtVerificarAnexov->get_result();
        
                            if ($resultVerificarAnexov->num_rows > 0) {
                                echo json_encode(['status' => 'error', 'message' => 'El suplente ya está registrado en la salida.']);
                            } else {
                                // Insertar el suplente en la tabla anexov
                                $sqlInsert = "INSERT INTO anexov (`fkAnexoIV`, `dni`, `apellidoNombre`, `edad`, `cargo`) VALUES (?, ?, ?, ?, ?)";
                                $stmtInsert = $conexion->prepare($sqlInsert);
        
                                if ($stmtInsert) {
                                    $cargo = 5; // Suplente
                                    $stmtInsert->bind_param('iisii', $idAnexoIV, $dni, $nombreApellido, $edad, $cargo);
        
                                    if ($stmtInsert->execute()) {
                                        // Obtener todos los participantes después de la inserción
                                        $sqlSelect = "SELECT dni, apellidoNombre, edad, cargo FROM anexov WHERE fkAnexoIV = ?";
                                        $stmtSelect = $conexion->prepare($sqlSelect);
                                        $stmtSelect->bind_param('i', $idAnexoIV);
                                        $stmtSelect->execute();
                                        $result = $stmtSelect->get_result();
        
                                        $participantes = [];
                                        while ($row = $result->fetch_assoc()) {
                                            $participantes[] = $row;
                                        }
        
                                        echo json_encode(['status' => 'success', 'message' => 'Suplente registrado correctamente', 'participantes' => $participantes]);
                                        $stmtSelect->close();
                                    } else {
                                        echo json_encode(['status' => 'error', 'message' => 'Error al insertar el registro: ' . $stmtInsert->error]);
                                    }
                                    $stmtInsert->close();
                                }
                            }
                            $stmtVerificarAnexov->close();
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta de verificación: ' . $conexion->error]);
                        }
                    }
                    $stmtVerificarPersonal->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta de verificación en personal: ' . $conexion->error]);
                }
                $conexion->close();
            }
        break;        

        case 'agregarGrupo':
            session_start();
            header('Content-Type: application/json');
        
            if (isset($data['personas']) && $data['opcion'] === 'agregarGrupo') {
                $personas = $data['personas'];
                $idAnexoIV = $_SESSION['idSalida'];
                $personasDuplicadas = [];
        
                // --------- Fase de verificación (antes de cualquier inserción) ---------
                foreach ($personas as $persona) {
                    $dni = intval($persona['dni']);
                    // Verificar duplicados
                    $sqlVerificar = "SELECT fkAnexoIV FROM `anexov` WHERE dni = ? AND fkAnexoIV = ?";
                    $stmtVerificar = $conexion->prepare($sqlVerificar);
                    $stmtVerificar->bind_param('ii', $dni, $idAnexoIV);
                    $stmtVerificar->execute();
                    $resultVerificar = $stmtVerificar->get_result();
        
                    if ($resultVerificar->num_rows > 0) {
                        $personasDuplicadas[] = $dni;
                    }
        
                    $stmtVerificar->close();
                }
        
                // Si hay personas duplicadas
                if (count($personasDuplicadas) > 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Algunas personas ya están registradas en la salida.']);
                    exit;
                }
        
                $consultaExitosa = true;
        
                foreach ($personas as $persona) {
                    $dni = intval($persona['dni']);
                    $nombreApellido = trim($persona['nombre'] . ' ' . $persona['apellido']);
                    $fechan = $persona['fechan'];
        
                    if (!$fechan || !DateTime::createFromFormat('Y-m-d', $fechan)) {
                        continue; 
                    }
        
                    $fechaActual = date("Y-m-d");
                    $fechaNacimiento = new DateTime($fechan);
                    $fechaHoy = new DateTime($fechaActual);
                    $edad = $fechaHoy->diff($fechaNacimiento)->y;
        
                    $sqlInsert = "INSERT INTO anexov (`fkAnexoIV`, `dni`, `apellidoNombre`, `edad`, `cargo`) VALUES (?, ?, ?, ?, ?)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $cargo = 3;
        
                    if ($stmtInsert === false) {
                        $consultaExitosa = false;
                        break;
                    }
        
                    $stmtInsert->bind_param('iisii', $idAnexoIV, $dni, $nombreApellido, $edad, $cargo);
        
                    if (!$stmtInsert->execute()) {
                        $consultaExitosa = false;
                        break;
                    }
        
                    $stmtInsert->close();
                }
        
                if ($consultaExitosa) {
                    echo json_encode(['status' => 'success', 'message' => 'Todas las personas procesadas correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al insertar las personas.']);
                }
        
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se recibieron personas o la opción no es válida.']);
            }
            break;
    }
?>