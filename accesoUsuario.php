<?php
include 'conexion.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $conn->real_escape_string($_POST['dni']);

    // Verificar si el DNI está en la tabla solicitantes
    $sql_solicitantes = "SELECT dni FROM solicitantes WHERE dni = ?";
    $stmt = $conn->prepare($sql_solicitantes);

    if ($stmt) {
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['dni'] = $dni; // Guardar el DNI en la sesión
            header("Location: listadoCursos.php");
        } else {
            $_SESSION['dni'] = $dni; // Guardar el DNI en la sesión para registrarlo
            header("Location: guardarDatosSolicitante.php");
        }

        $stmt->close();
        exit();
    } else {
        echo "Error en la consulta.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Usuario</title>
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
            max-width: 400px;
            margin: auto;
        }
        h3 {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="submit"] {
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
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Acceso Usuario</h3>
    <form method="POST" action="">
        <label for="dni">DNI:</label>
        <input type="text" name="dni" required>
        <input type="submit" value="Ingresar">
    </form>
    
    <?php if (isset($mensaje)): ?>
        <p class="error"><?php echo $mensaje; ?></p>
    <?php endif; ?>
</div>

</body>
</html>
