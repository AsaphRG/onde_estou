<?php
session_start();
require_once '../config.php';
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    $sql = $conn->prepare("INSERT INTO publisher (name) VALUES (?)");
    $sql->bind_param('s', $data['name']);

    executeQuery($sql, $data, 'publisher', $data['name']." salva!");
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
    exit();
}