<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdAlimente";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("conexão falhou " . $conn->connect_error);
}
?>
