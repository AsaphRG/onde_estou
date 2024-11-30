<?php
session_start();
require "../config.php";
$data = formTreatment($_POST);

if($data['csrf_token'] == $_SESSION['csrf_token']) {
    $id_note = $data['id_note'];
    $id_book = $data['id_book'];
    if ($id_note) {
        $query = $conn->prepare("DELETE FROM note WHERE id_note = ?");
        $query->bind_param('i', $id_note);

        try {
            $query->execute();
            $message = "Nota excluída.";
            header("Location: /PIE3/pages/book/book.php?id=$id_book&success=".urlencode($message));
            exit();
        } catch (Exception $error) {
            $message = "O seguinte erro ocorreu ao tentar excluir a nota ".$id_note.":<br>".$error->getMessage();
            header("Location: /PIE3/pages/book/book.php?id=$id_book&error=".urlencode($message));
            exit();
        }
    } else {
        header("Location: /PIE3/pages/book/book.php?id=$id_book&error=O identificador da nota a ser excluída está vazio.");
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/book.php?id=".$id_book."&error=".urlencode($message));
    exit();
}