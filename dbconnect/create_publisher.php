<?php
session_start();
require_once '../config.php';
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    $sql = $conn->prepare("INSERT INTO publisher (name) VALUES (?)");
    $sql->bind_param('s', $data['name']);

    if ($sql->execute() === TRUE) {
        $message = $data['name']." salva!";
        header("Location: /PIE3/pages/publisher/publishers.php?success=".urlencode($message));
        exit();
    } else {
        $message = "O seguinte erro ocorreu ao tentar incluir a editora ".$data['name'].":<br>".$sql->error;
        header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
    exit();
}