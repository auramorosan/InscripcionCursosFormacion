<?php
require 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baremación de Cursos</title>
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
            max-width: 800px;
            margin: auto;
        }
        h3, h4 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .feedback {
            font-weight: bold;
            margin-top: 15px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Baremación y listado solicitantes admitidos</h3>

        <?php
        // Obtener todos los cursos cuyo plazo de inscripción ha finalizado y están cerrados
        $sqlCursos = "SELECT codigo, nombre, numeroplazas FROM cursos WHERE abierto = 0 AND plazoinscripcion < CURDATE()";
        $resultCursos = $conn->query($sqlCursos);

        if ($resultCursos->num_rows === 0) {
            echo "<p class='error'>No hay cursos con el plazo de inscripción finalizado.</p>";
            exit();
        }

        while ($curso = $resultCursos->fetch_assoc()) {
            $codigoCurso = $curso['codigo'];
            $nombreCurso = $curso['nombre'];
            $numeroPlazas = $curso['numeroplazas'];

            echo "<h4>Curso: " . htmlspecialchars($nombreCurso) . " (" . $codigoCurso . ")</h4>";

            // Obtener los solicitantes inscritos en el curso
            $sqlSolicitantes = "SELECT s.dni, su.puntos, su.nombre, su.apellidos, su.coordinadortic,
                                su.grupotic, su.pbilin, su.cargo, su.nombrecargo, su.antiguedad, su.situacion
                                FROM solicitudes AS s JOIN solicitantes AS su ON s.dni = su.dni WHERE s.codigocurso = ?";
            $stmtSolicitantes = $conn->prepare($sqlSolicitantes);
            $stmtSolicitantes->bind_param("s", $codigoCurso);
            $stmtSolicitantes->execute();
            $resultSolicitantes = $stmtSolicitantes->get_result();

            if ($resultSolicitantes->num_rows === 0) {
                echo "<p>No hay solicitantes inscritos en este curso.</p>";
                continue;
            }
            //creamos un array vacio
            $solicitantes = [];
            //fetch_assoc() obtiene una fila de la base de datos en forma de array asociativo.
            //$row guarda los datos de un solicitante que devuelve fetch_assoc() en cada iteracion
            while ($row = $resultSolicitantes->fetch_assoc()) {
                //devuelve algo como $row = ['dni' => '12345678A','puntos' => '5','nombre' => 'Juan','apellidos' => 'Pérez'];
                $puntos = intval($row['puntos']);
                //interval() elimina decimales y convierte cadenas numéricas a enteros
                if ($row['coordinadortic']) $puntos += 4;
                if ($row['grupotic']) $puntos += 3;
                if ($row['pbilin']) $puntos += 3;
                if ($row['cargo']) {
                    //strtolower() convierte todo en minusculas
                    $cargo = strtolower($row['nombrecargo']);
                    if ($cargo === 'director' || $cargo === 'jefe de estudios' || $cargo === 'secretario') {
                        $puntos += 2;
                    } elseif ($cargo === 'jefe de departamento') {
                        $puntos += 1;
                    }
                }
                $puntos += intval($row['antiguedad']);
                if (strtolower($row['situacion']) === 'activo') $puntos += 1;

                $solicitantes[] = [
                    'dni' => $row['dni'],
                    'nombre' => $row['nombre'],
                    'apellidos' => $row['apellidos'],
                    'puntos' => $puntos
                ];
            }
            //ordena el array de solicitantes de mayor a menor
            usort($solicitantes, fn($a, $b) => $b['puntos'] <=> $a['puntos']);

            echo "<table>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Puntos</th>
                        <th>Estado</th>
                    </tr>";

            foreach ($solicitantes as $index => $solicitante) {
                $estado = ($index < $numeroPlazas) ? "Admitido" : "En lista de espera";
                $admitido = ($estado === "Admitido") ? 1 : 0;
                
                $sqlActualizar = "UPDATE solicitudes SET admitido = ? WHERE dni = ? AND codigocurso = ?";
                $stmtActualizar = $conn->prepare($sqlActualizar);
                $stmtActualizar->bind_param("isi", $admitido, $solicitante['dni'], $codigoCurso);
                $stmtActualizar->execute();

                echo "<tr>
                        <td>" . htmlspecialchars($solicitante['dni']) . "</td>
                        <td>" . htmlspecialchars($solicitante['nombre']) . "</td>
                        <td>" . htmlspecialchars($solicitante['apellidos']) . "</td>
                        <td>" . $solicitante['puntos'] . "</td>
                        <td>" . $estado . "</td>
                    </tr>";
            }
            echo "</table>";
        }
        echo "<p class='success'>Baremación completada para todos los cursos con plazo de inscripción finalizado.</p>";
        ?>
    </div>
</body>
</html>
