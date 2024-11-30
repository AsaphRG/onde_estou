<?php
session_start();
require_once '../config.php';
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {

    $name = $data['name'];

    $sql = $conn->prepare("INSERT INTO author (name) VALUES (?)");
    $sql->bind_param('s', $data['name']);

    executeQuery($sql, $data, 'author', $data['name']." criado!");
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
    exit();
}