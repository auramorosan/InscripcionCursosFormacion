<?php
require 'conexion.php'; // Incluir la conexión a la base de datos
// Verificar que el usuario esté autenticado
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activar/Desactivar Cursos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
            text-align: center;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h3 {
            color: #333;
        }
        form {
            text-align: left;
        }
        label {
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
            margin-right: 10px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 10px 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .feedback {
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Activar o Desactivar Cursos</h3>

    <?php
    
    // Procesar formularios
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'];
            $codigo = $_POST['curso'];

            // Validar que se haya seleccionado un curso
            if (!empty($codigo)) {
                // ACTIVAR CURSO
                if ($accion == 'activar') {
                    $sql = "UPDATE cursos SET abierto = 1 WHERE codigo = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $codigo);

                    if ($stmt->execute()) {
                        echo "<p class='feedback' style='color: green;'>Curso activado exitosamente.</p>";
                    } else {
                        echo "<p class='feedback' style='color: red;'>Error al activar el curso: " . $conn->error . "</p>";
                    }
                }

                // DESACTIVAR CURSO
                if ($accion == 'desactivar') {
                    $sql = "UPDATE cursos SET abierto = 0 WHERE codigo = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $codigo);

                    if ($stmt->execute()) {
                        echo "<p class='feedback' style='color: green;'>Curso desactivado exitosamente.</p>";
                    } else {
                        echo "<p class='feedback' style='color: red;'>Error al desactivar el curso: " . $conn->error . "</p>";
                    }
                }
            } else {
                echo "<p class='feedback' style='color: red;'>Seleccione un curso.</p>";
            }
        }
    }
    ?>

    <!-- Formulario para activar/desactivar cursos -->
    <form method="POST" action="">
        <label for="curso">Seleccione un curso:</label>
        <select name="curso" required>
            <?php
            // Obtener lista de cursos
            $sql = "SELECT * FROM cursos";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $estado = $row['abierto'] ? " (Activo)" : " (Inactivo)";
                echo "<option value='" . $row['codigo'] . "'>" . $row['nombre'] . $estado . "</option>";
            }
            ?>
        </select><br><br>

        <!-- Botones para activar o desactivar -->
        <input type="submit" name="accion" value="activar">
        <input type="submit" name="accion" value="desactivar">
    </form>
</div>

</body>
</html>
