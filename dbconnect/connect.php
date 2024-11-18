<?php
$servername = "localhost";
$database = "pie3";
$username = "root";
$password = "";


$conn = new mysqli($servername, $username, $password, $database);

if (!$conn) {
    die("Conexão falhou. Erro: " . mysqli_connect_error());
}
