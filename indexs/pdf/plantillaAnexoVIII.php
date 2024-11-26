<?php
    require('fpdf/fpdf.php');
    require('fpdi/src/autoload.php');
    include('../../php/verificarSessionPDF.php');

    // FPDI y FPDF
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfReader\PdfReader;

    $idSalida = $_SESSION['idSalida'];
    $sqlAnexoVIII = "SELECT * FROM anexoviii WHERE fkAnexoIV = $idSalida";
    $resultadoAnexoVIII = mysqli_query($conexion, $sqlAnexoVIII);

    $pdf = new Fpdi();

    $pdf->SetMargins(20, 20, 20);
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();

    // Encabezado
    $pdf->Image('../../imagenes/eest.png', 20, 8, 20);
    $pdf->Image('../../imagenes/logoprovincia.jpg', 102, 8, 90); // Logo

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 40, mb_convert_encoding('IF-2024-35031394-GDEBA-CGCYEDGCYE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
    
    $pdf->Ln(-10);

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, mb_convert_encoding('ANEXO VIII', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 12); 
    $pdf->MultiCell(0, 7, mb_convert_encoding('PLANILLA INFORME DE TRANSPORTE A CONTRATAR', 'ISO-8859-1', 'UTF-8'), 0, 'C');
    
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);

    while ($filaAnexoVIIIArrays = mysqli_fetch_assoc($resultadoAnexoVIII)) {
        $fechaHabilitacion = date("d/m/y", strtotime($filaAnexoVIIIArrays['fechaHabilitacion']));
        $vigenciaVTV = date("d/m/y", strtotime($filaAnexoVIIIArrays['vigenciaVTV']));
        $vigenciaConductor = date("d/m/y", strtotime($filaAnexoVIIIArrays['vigenciaConductor']));

        $pdf->Cell(0, 10, mb_convert_encoding('Nombre de la empresa o razón social: '.$filaAnexoVIIIArrays['nombreEmpresa'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Nombre del gerente o responsable: '.$filaAnexoVIIIArrays['nombreGerente'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Domicilio del propietario o la empresa: '.$filaAnexoVIIIArrays['domicilioEmpresa'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Teléfono del propietario o la empresa: '.$filaAnexoVIIIArrays['telefonoEmpresa'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Domicilio del gerente o responsable: '.$filaAnexoVIIIArrays['domicilioGerente'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Teléfono: ', 'ISO-8859-1', 'UTF-8').$filaAnexoVIIIArrays['telefono'], 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Teléfono móvil: ', 'ISO-8859-1', 'UTF-8').$filaAnexoVIIIArrays['telefonoMovil'], 0, 1);
        $pdf->MultiCell(0, 8, mb_convert_encoding('Titularidad del vehículo (Micro, ómnibus, combi, automóvil, camioneta, barco, lancha, avión, entre otros): '.$filaAnexoVIIIArrays['titularidadVehiculo'].'', 'ISO-8859-1', 'UTF-8'), 0);

        $pdf->Ln(5);
    
        $pdf->MultiCell(0, 8, mb_convert_encoding('Habilitación del/los vehículos (número de registro, fecha, tipo de habilitación, cantidad de asientos, vigencia de VTV hasta el regreso):', 'ISO-8859-1', 'UTF-8'), 0);
    
        $pdf->Ln(2);

        $nroRegistro = $filaAnexoVIIIArrays['nroRegistro'];
        $fechaHabilitacion = $filaAnexoVIIIArrays['fechaHabilitacion'];
        $tipoHabilitacion = $filaAnexoVIIIArrays['tipoHabilitacion'];
        $cantAsientos = $filaAnexoVIIIArrays['cantAsientos'];
        $vigenciaVTV = $filaAnexoVIIIArrays['vigenciaVTV'];
        $aseguradora = $filaAnexoVIIIArrays['aseguradora'];
        $nroPoliza = $filaAnexoVIIIArrays['nroPoliza'];
        $tipoSeguro = $filaAnexoVIIIArrays['tipoSeguro'];

        $nombreConductor = $filaAnexoVIIIArrays['nombreConductor'];
        $dniConductor = $filaAnexoVIIIArrays['dniConductor'];
        $carnetConducir = $filaAnexoVIIIArrays['carnetConducir'];
        $vigenciaConductor = $filaAnexoVIIIArrays['vigenciaConductor'];
        
        if (!empty($nroRegistro)) {
            // Dividir cada campo por comas
            $registroArray = explode('%', $nroRegistro);
            $fechaHabArray = explode('%', $fechaHabilitacion);
            $tipoHabArray = explode('%', $tipoHabilitacion);
            $cantArray = explode('%', $cantAsientos);
            $vigenciaArray = explode('%', $vigenciaVTV);
            $aseguradoraArray = explode('%', $aseguradora);
            $polizaArray = explode('%', $nroPoliza);
            $seguroArray = explode('%', $tipoSeguro);
    
            // Crear un array multidimensional con todos los datos
            $vehiculos = [];
            $count = max(count($registroArray), count($fechaHabArray), count($tipoHabArray), count($cantArray), count($vigenciaArray), count($aseguradoraArray), count($polizaArray), count($seguroArray));
    
            for ($i = 0; $i < $count; $i++) {
                $vehiculos[] = [
                    'registro' => isset($registroArray[$i]) ? $registroArray[$i] : '',
                    'fechaHabilitacion' => isset($fechaHabArray[$i]) ? date("d/m/y", strtotime($fechaHabArray[$i])) : '',
                    'tipoHabilitacion' => isset($tipoHabArray[$i]) ? $tipoHabArray[$i] : '',
                    'cantAsientos' => isset($cantArray[$i]) ? $cantArray[$i] : '',
                    'vigenciaVTV' => isset($vigenciaArray[$i]) ? date("d/m/y", strtotime($vigenciaArray[$i])) : '',
                    'aseguradora' => isset($aseguradoraArray[$i]) ? $aseguradoraArray[$i] : '',
                    'nroPoliza' => isset($polizaArray[$i]) ? $polizaArray[$i] : '',
                    'tipoSeguro' => isset($seguroArray[$i]) ? $seguroArray[$i] : ''
                ];
            }

            // Iterar sobre el array de vehículos
            foreach ($vehiculos as $index => $vehiculo) {
                $posicion = $index + 1;
                $pdf->Cell(0, 10, mb_convert_encoding('Vehículo Nº'.$posicion, 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Registro del Vehículo: ' . $vehiculo['registro'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Fecha de Habilitación: ' . $vehiculo['fechaHabilitacion'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Tipo de Habilitación: ' . $vehiculo['tipoHabilitacion'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Cantidad de Asientos: ' . $vehiculo['cantAsientos'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Vigencia de VTV: ' . $vehiculo['vigenciaVTV'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Aseguradora: ' . $aseguradora, 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Póliza: ' . $vehiculo['nroPoliza'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 10, chr(149), 0, 0);
                $pdf->Cell(0, 10, mb_convert_encoding('Tipo de Seguro: ' . $vehiculo['tipoSeguro'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Ln(5);
            }
        }

        if (!empty($dniConductor)) {
            // Dividir cada campo por comas
            $nombreArray = explode('%', $nombreConductor);
            $dniArray = explode('%', $dniConductor);
            $carnetArray = explode('%', $carnetConducir);
            $vencimientoArray = explode('%', $vigenciaConductor);

            $conductores = [];
            $countConductores = max(count($nombreArray), count($dniArray), count($carnetArray), count($vencimientoArray));
    
            for ($i = 0; $i < $countConductores; $i++) {
                $conductores[] = [
                    'nombre' => isset($nombreArray[$i]) ? $nombreArray[$i] : '',
                    'dni' => isset($dniArray[$i]) ? $dniArray[$i] : '',
                    'carnet' => isset($carnetArray[$i]) ? $carnetArray[$i] : '',
                    'vencimiento' => isset($vencimientoArray[$i]) ? date("d/m/y", strtotime($vencimientoArray[$i])) : '',
                ];
            }
    
            // Iterar sobre el array de vehículos
            foreach ($conductores as $index => $conductor) {
                $posicion = $index + 1;
                $pdf->Cell(0, 8, mb_convert_encoding('Conductor Nº'.$posicion, 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 8, chr(149), 0, 0);
                $pdf->Cell(0, 8, mb_convert_encoding('Nombre del conductor/ra/res/ras: ' . $conductor['nombre'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 8, chr(149), 0, 0);
                $pdf->Cell(0, 8, mb_convert_encoding('DNI del/los conductor/ra/es/as: ' . $conductor['dni'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Cell(5, 8, chr(149), 0, 0);
                $pdf->Cell(0, 8, mb_convert_encoding('Número/s de carnet de conducir y vigencia: ' . $conductor['carnet'] . ', '.$conductor['vencimiento'], 'ISO-8859-1', 'UTF-8'), 0, 1);
                $pdf->Ln(5);
            }
        }

        $pdf->Ln(5);
    }
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, mb_convert_encoding('Aclaración:', 'ISO-8859-1', 'UTF-8'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, mb_convert_encoding('El presente anexo debe ser completado de modo digital por la empresa de transporte y/o por las autoridades de la escuela, una vez visada la documentación correspondiente (la cual se registra en la Declaración Jurada, anexo IX).', 'ISO-8859-1', 'UTF-8'), 0);
    $pdf->MultiCell(0, 8, mb_convert_encoding('Adjuntar fotocopia de Constancia de habilitaciones, carnet de conductor, DNI del conductor/ra o conductores.', 'ISO-8859-1', 'UTF-8'), 0);
    $pdf->MultiCell(0, 8, mb_convert_encoding('Si se contratare transporte público de pasajeros se consignarán los datos de los respectivos pasajes o boletos.', 'ISO-8859-1', 'UTF-8'), 0);

    $rutaArchivo = '../../archivosPDFAnexoVIII/adjuntoPDFsalida' . $idSalida . '.pdf';

    // Check if the file exists and use setSourceFile and importPage methods correctly
    if (file_exists($rutaArchivo)) {
        $pageCount = $pdf->setSourceFile($rutaArchivo); // FPDI setSourceFile() method
        
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i); // Import each page
            $pdf->AddPage();
            
            // Use the template without margins by setting the coordinates to (0, 0) and scaling to full width and height
            $pdf->useTemplate($tplIdx, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
        }
    }
    
    $pdf->Output('I', 'anexoVIII.pdf');
?>
