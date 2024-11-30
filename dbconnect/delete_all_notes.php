<?php
session_start();
require "../config.php";
$data = formTreatment($_POST);

if($data['csrf_token'] == $_SESSION['csrf_token']) {
    $id_book = $data['id_book'];
    
    $query = $conn->prepare("DELETE FROM note WHERE id_book = ?");
    $query->bind_param('i', $id_book);

    try {
        $query->execute();
        $message = "Todas as notas foram excluídas.";
        header("Location: /PIE3/pages/book/book.php?id=$id_book&success=".urlencode($message));
        exit();
    } catch (Exception $error) {
        $message = "O seguinte erro ocorreu ao tentar excluir todas as notas: ".$error->getMessage();
        header("Location: /PIE3/pages/book/book.php?id=$id_book&error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/book.php?id=".$id_book."&error=".urlencode($message));
    exit();
}