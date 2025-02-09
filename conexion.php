<?php
//Conexión a la base de datos
$servername = "127.0.0.1:3307";
//$servername="localhost";
$username = "root";
$password = "root";
$dbname = "cursoscp";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
session_start();
