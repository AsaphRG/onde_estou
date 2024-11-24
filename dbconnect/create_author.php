<?php
require_once '../config.php';
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {

    $name = $data['name'];

    $sql = $conn->prepare("INSERT INTO author (name) VALUES (?)");
    $sql->bind_param('s', $data['name']);

    if ($sql->execute() === TRUE) {
        $message = $data['name']." salvo!";
        header("Location: /PIE3/pages/author/authors.php?success=".urlencode($message));
        exit();
    } else {
        $message = "O seguinte erro ocorreu ao tentar incluir o(a) autor(a) ".$data['name'].":<br>".$sql->error;
        header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
    exit();
}