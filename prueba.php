<?php
require 'conexion.php'; // Incluir la conexión a la base de datos

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['dni'])) {
    echo "<p class='error'>Debes iniciar sesión para realizar esta acción.</p>";
    exit();
}

$dni = $_SESSION['dni']; // Recuperar el DNI desde la sesión
$mensaje = ""; // Variable para mostrar mensajes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigocurso = $_POST['curso'] ?? '';

    if (!empty($codigocurso)) {
        $fechasolicitud = date('Y-m-d'); // Fecha actual
        $admitido = 0; // Estado inicial: no admitido

        // Insertar la solicitud en la base de datos
        $sql = "INSERT INTO solicitudes (dni, codigocurso, fechasolicitud, admitido)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $dni, $codigocurso, $fechasolicitud, $admitido);

        if ($stmt->execute()) {
            // Obtener el nombre del curso
            $sqlCurso = "SELECT nombre FROM cursos WHERE codigo = ?";
            $stmtCurso = $conn->prepare($sqlCurso);
            $stmtCurso->bind_param("s", $codigocurso);
            $stmtCurso->execute();
            $resultCurso = $stmtCurso->get_result();
            $curso = $resultCurso->fetch_assoc();

            // Mensaje de confirmación
            $mensaje = "<div class='resguardo'>
                            <h3>Resguardo de Inscripción</h3>
                            <p><strong>DNI:</strong> $dni</p>
                            <p><strong>Curso:</strong> " . htmlspecialchars($curso['nombre']) . "</p>
                            <p><strong>Fecha de Solicitud:</strong> $fechasolicitud</p>
                            <p><strong>Estado:</strong> Pendiente de Aprobación</p>
                        </div>";
        } else {
            $mensaje = "<p class='error'>Error al registrar la solicitud: " . $conn->error . "</p>";
        }
        $stmt->close();
    } else {
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
<?php