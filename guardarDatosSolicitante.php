<?php
require 'conexion.php'; // Conexión a la base de datos

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $apellidos = $_POST['apellidos'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $codigocentro = $_POST['codigocentro'];
    $coordinadortic = isset($_POST['coordinadortic']) ? 1 : 0;
    $grupotic = isset($_POST['grupotic']) ? 1 : 0;
    $nombregrupo = $_POST['nombregrupo'];
    $pbilin = isset($_POST['pbilin']) ? 1 : 0;
    $cargo = isset($_POST['cargo']) ? 1 : 0;
    $nombrecargo = $_POST['nombrecargo'];
    $situacion = $_POST['situacion'];
    $antiguedad = intval($_POST['antiguedad']);
    $especialidad = $_POST['especialidad'];
    $puntos = intval($_POST['puntos']);//solo devuelve numeros si se encuentra mas caracteres y devuelve solo la parte entera de una nr decimal

    // Validar campos requeridos
    if (!empty($dni) && !empty($apellidos) && !empty($nombre) && !empty($telefono) && !empty($correo)) {
        $sql = "INSERT INTO solicitantes
                (dni, apellidos, nombre, telefono, correo, codigocentro, coordinadortic, grupotic, nombregrupo, pbilin, cargo, nombrecargo, situacion, antiguedad, especialidad, puntos) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiisiissisi", $dni, $apellidos, $nombre, $telefono, $correo, $codigocentro, $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo, $nombrecargo, $situacion, $antiguedad, $especialidad, $puntos);

        if ($stmt->execute()) {
            $_SESSION['dni'] = $dni;
            $mensaje = "Solicitante agregado exitosamente.";
        } else {
            $mensaje = "Error al agregar el solicitante: " . $conn->error;
        }
    } else {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Solicitante</title>
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
        input, select {
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
        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Agregar Solicitante</h3>

    <?php if (isset($mensaje)): ?>
        <p class="<?php echo strpos($mensaje, 'exitosamente') !== false ? 'success' : 'error'; ?>">
            <?php echo $mensaje; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="dni">DNI:</label>
        <input type="text" name="dni" maxlength="9" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" maxlength="50" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" maxlength="50" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" maxlength="12" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" maxlength="50" required>

        <label for="codigocentro">Código Centro:</label>
        <input type="text" name="codigocentro" maxlength="8" required>

        <div class="checkbox-group">
            <label><input type="checkbox" name="coordinadortic" value="1"> Coordinador TIC</label>
        </div>

        <div class="checkbox-group">
            <label><input type="checkbox" name="grupotic" value="1"> Grupo TIC</label>
        </div>

        <label for="nombregrupo">Nombre Grupo:</label>
        <input type="text" name="nombregrupo" maxlength="25">

        <div class="checkbox-group">
            <label><input type="checkbox" name="pbilin" value="1"> Programa Bilingüe</label>
        </div>

        <div class="checkbox-group">
            <label><input type="checkbox" name="cargo" value="1"> Cargo</label>
        </div>

        <label for="nombrecargo">Nombre Cargo:</label>
        <input type="text" name="nombrecargo" maxlength="50">

        <label for="situacion">Situación:</label>
        <select name="situacion" required>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>

        <label for="antiguedad">Antigüedad (años):</label>
        <input type="number" name="antiguedad" min="0" max="99" required>

        <label for="especialidad">Especialidad:</label>
        <input type="text" name="especialidad" maxlength="50" required>

        <label for="puntos">Puntos:</label>
        <input type="number" name="puntos" min="0" max="999" required>

        <input type="submit" value="Agregar Solicitante">
    </form>

    <a href="index.php">Volver a la página principal</a>
</div>

</body>
</html>
