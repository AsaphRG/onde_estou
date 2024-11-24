<?php
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
            if ($sql->execute()) {
                $message = $name." foi atualizado!";
                header("Location: /PIE3/pages/author/authors.php?success=".urlencode($message));
                exit();
            } else {
                $message = "O seguinte erro ocorreu ao tentar incluir o(a) autor(a) ".$name.":<br>".$sql->error;
                header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
                exit();
            }
        } else {
            $message = "Não há campos a serem atualizados.";
            header("Location: /PIE3/pages/author/authors.php?info=".urlencode($message));
            exit();
        }
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
    exit();
}