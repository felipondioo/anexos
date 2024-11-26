<?php
    session_start();
    include 'conexion.php';

    if (isset($_GET['idSalida'])) {
        $_SESSION['idSalida'] = $_GET['idSalida'];
        $idSalida = $_SESSION['idSalida'];
        echo $idSalida;

        $sql = "SELECT denominacionProyecto FROM anexoiv WHERE idAnexoIV = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idSalida);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $denominacionProyecto = $row['denominacionProyecto'];

                $_SESSION['denominacionProyecto'] = $denominacionProyecto;

                header('Location: ../indexs/profesores/menuSalida.php');
                exit;
            } else {
                echo "Error";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo ('Hubo un error al capturar el id...');
    }

    $conexion->close();
?>