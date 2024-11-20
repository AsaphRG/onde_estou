<?php
require_once '../config.php';
$data = formTreatment($_POST);

$id = $data['id_publisher'];
$name = $data['name'];

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
        if ($sql->execute()) {
            $message = $name." foi atualizada!";
            header("Location: /PIE3/pages/publisher/publishers.php?success=".urlencode($message));
            exit();
        } else {
            $message = "O seguinte erro ocorreu ao tentar incluir a editora $name:<br>$sql->error";
            header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
            exit();
        }
    } else {
        $message = "Não há campos a serem atualizados.";
        header("Location: /PIE3/pages/publisher/publishers.php?info=".urlencode($message));
        exit();
    }
}