<!DOCTYPE html>
<html lang="">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            color: #333;
            margin-top: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: white;
            background: #007BFF;
            padding: 10px;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #e9ecef;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Bienvenidos!!!</h2>
    <div class="container">
        <a href="accesoAdmin.php">Login administradores</a><br><br>
        <a href="accesoUsuario.php">Login usuario-profesor</a><br><br>
        <a href="guardarDatosSolicitante.php">Registrate!</a>
        <h1>Cursos disponibles</h1>
        <?php
        //http://localhost/PracticaFinal1/ poner en Google para entrar
            include 'conexion.php';  // Conexión a la base de datos
            $sql = "SELECT codigo, nombre FROM cursos";  // Consulta SQL para obtener los cursos
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostrar los cursos en una lista
                echo '<ul>';  // Comienza la lista
                while ($row = $result->fetch_assoc()) {
                    echo '<li>' . $row['nombre'] . ' (Código: ' . $row['codigo'] . ')</li>';  // Mostrar cada curso
                }
                echo '</ul>';  // Cierra la lista
            } else {
                echo "No hay cursos disponibles.";  // Si no hay resultados
            }

            $conn->close();
        ?>
    </div>
</body>
</html>