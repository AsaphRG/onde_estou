<?php
session_start();
require_once '../config.php';
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    $id = $data['id_author'];
    $name = $data['name'];

    if ($id) {
        $query_author = $conn->prepare("SELECT * FROM author WHERE id_author = ?");
        $query_author->bind_param('i', $id);
        $query_author->execute();
        $result_author = $query_author->get_result();
        $author = $result_author->fetch_assoc();
    }

    if ($author) {
        if ($author['name'] != $name) {
            $sql = $conn->prepare("UPDATE author SET name = ? WHERE id_author = ?");
            $sql->bind_param('si', $name, $id);
            executeQuery($sql, $author, 'author', "$name foi atualizado!");
        } else {
            $message = "Não há campos a serem atualizados.";
            header("Location: /PIE3/pages/author/authors.php?info=".urlencode($message));
            exit();
        }
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/author/author.php?id=$id&error=".urlencode($message));
    exit();
}