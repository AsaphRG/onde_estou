<?php
session_start();
require_once '../config.php';
$data = formTreatment($_POST);

$id = $data['id_publisher'];
$name = $data['name'];

if($data['csrf_token'] == $_SESSION['csrf_token']) {
    if ($id) {
        $query_publisher = $conn->prepare("SELECT * FROM publisher WHERE id_publisher = ?");
        $query_publisher->bind_param('i', $id);
        $query_publisher->execute();
        $result_publisher = $query_publisher->get_result();
        $publisher = $result_publisher->fetch_assoc();
    }

    if ($publisher) {
        if ($publisher['name'] != $name) {
            $sql = $conn->prepare("UPDATE publisher SET name = ? WHERE id_publisher = ?");
            $sql->bind_param('si', $name, $id);
            executeQuery($sql, $publisher, 'publisher', "$name foi atualizada!");
        } else {
            $message = "Não há campos a serem atualizados.";
            header("Location: /PIE3/pages/publisher/publishers.php?info=".urlencode($message));
            exit();
        }
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/publisher/publisher.php?id=$id&error=".urlencode($message));
    exit();
}