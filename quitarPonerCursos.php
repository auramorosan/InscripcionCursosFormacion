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
    <title>Administrar Cursos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h3, h4 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: left;
        }
        label {
            font-weight: bold;
            display: inline-block;
            margin-bottom: 8px;
            margin-top: 10px;
        }
        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="checkbox"] {
            margin-top: 15px;
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
            width: 100%;
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
    <?php
    // Procesar formularios
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'];

            // AGREGAR CURSO
            if ($accion == 'agregar') {
                $codigo = $_POST['codigo'];
                $nombre = $_POST['nombre'];
                $abierto = isset($_POST['abierto']) ? 1 : 0;
                $numeroplazas = intval($_POST['numeroplazas']);
                $plazoinscripcion = $_POST['plazoinscripcion'];

                // Validar campos requeridos
                if (!empty($codigo) && !empty($nombre) && !empty($numeroplazas) && !empty($plazoinscripcion)) {
                    $sql = "INSERT INTO cursos (codigo, nombre, abierto, numeroplazas, plazoinscripcion) 
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssis", $codigo, $nombre, $abierto, $numeroplazas, $plazoinscripcion);

                    if ($stmt->execute()) {
                        echo "<p class='feedback' style='color: green;'>Curso agregado exitosamente.</p>";
                    } else {
                        echo "<p class='feedback' style='color: red;'>Error al agregar el curso: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p class='feedback' style='color: red;'>Todos los campos son obligatorios.</p>";
                }
            }

            // ELIMINAR CURSO
            if ($accion == 'eliminar') {
                $codigo = $_POST['curso'];

                $sql = "DELETE FROM cursos WHERE codigo = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $codigo);

                if ($stmt->execute()) {
                    echo "<p class='feedback' style='color: green;'>Curso eliminado exitosamente.</p>";
                } else {
                    echo "<p class='feedback' style='color: red;'>Error al eliminar el curso: " . $conn->error . "</p>";
                }
            }
        }
    }
    ?>

    <!-- Formulario para añadir un curso -->
    <h4>Añadir Curso</h4>
    <form method="POST" action="">
        <input type="hidden" name="accion" value="agregar">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" required><br>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label for="abierto">Abierto:</label>
        <input type="checkbox" name="abierto" value="1"><br>
        <label for="numeroplazas">Número de Plazas:</label>
        <input type="number" name="numeroplazas" required><br>
        <label for="plazoinscripcion">Plazo de Inscripción:</label>
        <input type="date" name="plazoinscripcion" required><br>
        <input type="submit" value="Añadir Curso">
    </form>

    <!-- Formulario para eliminar un curso -->
    <h4>Eliminar Curso</h4>
    <form method="POST" action="">
        <input type="hidden" name="accion" value="eliminar">
        <label for="curso">Seleccionar Curso:</label>
        <select name="curso" required>
            <?php
            // Obtener lista de cursos existentes
            $sql = "SELECT * FROM cursos";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['codigo'] . "'>" . $row['nombre'] . " - " . $row['codigo'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Eliminar Curso">
    </form>
</div>

</body>
</html>
