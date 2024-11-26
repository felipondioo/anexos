<?php
session_start();

if (isset($_POST['idSalida'])) {
    $_SESSION['idSalida'] = $_POST['idSalida'];
    echo "Variable de sesión establecida correctamente.";
} else {
    echo "Error: idSalida no proporcionado.";
}
?>