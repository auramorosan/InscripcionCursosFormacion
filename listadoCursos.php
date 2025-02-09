
<?php
//pasar la base de datos nueva a phpmyadmin
require 'conexion.php'; // Incluir la conexión a la base de datos
require 'fpdf186/fpdf.php'; // Incluir la librería FPDF
//ruta casa
require 'C:\xampp\htdocs\practicaFinal1\src\PHPMailer.php';
require 'C:\xampp\htdocs\practicaFinal1\src\SMTP.php';
require 'C:\xampp\htdocs\practicaFinal1\src\Exception.php';
//ruta clase
//require '/var/www/html/practicaFinal1/src/PHPMailer.php';
//require '/var/www/html/practicaFinal1/src/SMTP.php';
//require '/var/www/html/practicaFinal1/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['dni'])) {
    echo "<p class='error'>Debes iniciar sesión para realizar esta acción.</p>";
    exit();
}

$dni = $_SESSION['dni']; // Recuperar el DNI desde la sesión
$mensaje = ""; // Variable para mostrar mensajes

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $codigocurso = $_POST['curso'] ?? '';

    if(!empty($codigocurso)){
        $fechasolicitud = date('Y-m-d'); // Fecha actual
        $admitido = 0; // Estado inicial: no admitido

        // Insertar la solicitud en la base de datos
        $sql = "INSERT INTO solicitudes (dni, codigocurso, fechasolicitud, admitido)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        //bind_param vincula variables a los parametros de una consulta preparada
        $stmt->bind_param("sssi", $dni, $codigocurso, $fechasolicitud, $admitido);
        if($stmt->execute()){
            // Obtener el nombre del curso
            $sqlCurso = "SELECT nombre FROM cursos WHERE codigo = ?";
            $stmtCurso = $conn->prepare($sqlCurso);
            $stmtCurso->bind_param("s", $codigocurso);
            $stmtCurso->execute();
            $resultCurso = $stmtCurso->get_result();
            $curso = $resultCurso->fetch_assoc();

            // Crear el PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(200, 10, 'Resguardo de Inscripcion', 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(100, 10, 'DNI: ' . $dni, 0, 1);
            $pdf->Cell(100, 10, 'Curso: ' . htmlspecialchars($curso['nombre']), 0, 1);
            $pdf->Cell(100, 10, 'Fecha de Solicitud: ' . $fechasolicitud, 0, 1);
            $pdf->Cell(100, 10, 'Estado: Pendiente de Aprobacion', 0, 1);
            $pdfOutput = 'pdfGenerado/pdf' . $dni . '.pdf'; // Cambia la ruta según necesites
            $pdf->Output('F', $pdfOutput);

            // Obtener el correo del usuario desde la base de datos
            $sqlEmail = "SELECT correo FROM solicitantes WHERE dni = ?";
            $stmtEmail = $conn->prepare($sqlEmail);
            $stmtEmail->bind_param("s", $dni);
            $stmtEmail->execute();
            $resultEmail = $stmtEmail->get_result();
            $usuario = $resultEmail->fetch_assoc();
            $stmtEmail->close();

            if ($usuario) {
                $emailUsuario = $usuario['correo']; // Email del usuario registrado en la base de datos
            } else {
                $mensaje = "<p class='error'>No se encontró el correo electrónico del usuario.</p>";
                exit();
            }

            // Enviar correo con el PDF adjunto
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->SMTPDebug = 0; // Cambiar a 2 o más para depuración
                $mail->Debugoutput = 'html';
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                ];
                $mail->isSMTP();
                //ruta casa
                $mail->Host = '127.0.0.1';
                //ruta clase
                //$mail->Host = 'localhost';
                $mail->SMTPAuth = true;
                //username y password de casa
                $mail->Username = 'nadia@auramorosan.com';
                $mail->Password = 'nadia';
                //username y password de clase
                //$mail->Username = 'auramorosan@domenico.es';
                //$mail->Password = 'auramorosan';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                //puerto SMTP casa
                $mail->Port = 465;
                //puerto SMTP clase
                //$mail->Port=587;
                // Configuración del remitente y destinatario casa
                $mail->setFrom('nadia@auramorosan.com', 'Nadia');
                $mail->addAddress($emailUsuario, 'Usuario');
                // Configuración del remitente y destinatario clase
                //$mail->setFrom('auramorosan@domenico.es', 'Aura Morosan');
                //$mail->addAddress('mariaperez@domenico.es', 'Maria Perez'); // Correo destino

                $mail->isHTML(true);
                $mail->Subject = 'Resguardo de Inscripción';
                $mail->Body = '<p>Se ha generado un nuevo resguardo de inscripción para el curso.</p>';

                // Adjuntar el archivo PDF
                $mail->addAttachment($pdfOutput);

                // Enviar el correo
                $mail->send();

                // Mensaje de confirmación
                $mensaje = "<div class='resguardo'>
                                <h3>Resguardo de Inscripción</h3>
                                <p><strong>DNI:</strong> $dni</p>
                                <p><strong>Curso:</strong> " . htmlspecialchars($curso['nombre']) . "</p>
                                <p><strong>Fecha de Solicitud:</strong> $fechasolicitud</p>
                                <p><strong>Estado:</strong> Pendiente de Aprobación</p>
                                <p>Un correo con el resguardo ha sido enviado a Nadia.</p>
                            </div>";
            }catch(Exception $e) {
                $mensaje = "<p class='error'>Error al enviar el correo: {$mail->ErrorInfo}</p>";
            }
        }else{
            $mensaje = "<p class='error'>Error al registrar la solicitud: " . $conn->error . "</p>";
        }
        $stmt->close();
    }else{
        $mensaje = "<p class='error'>Por favor, seleccione un curso.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cursos Abiertos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
            text-align: left;
        }
        h3 {
            text-align: center;
        }
        select, input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .resguardo {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Seleccione un Curso</h3>
        <?php echo $mensaje; ?>
        <form method="POST" action="">
            <label for="curso">Seleccione un Curso:</label>
            <select name="curso" id="curso" required>
                <?php
                $sqlCursos = "SELECT codigo, nombre FROM cursos WHERE abierto = 1";
                $resultCursos = $conn->query($sqlCursos);

                while ($row = $resultCursos->fetch_assoc()) {
                    echo "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                }
                ?>
            </select><br><br>
            <input type="submit" value="Inscribirse">
        </form>
    </div>
</body>
</html>
