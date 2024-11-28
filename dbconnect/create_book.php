<?php
session_start();
require_once '../config.php';

$data = formTreatment($_POST);
if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    $finished = isset($data['finished']);

    $sql = $conn->prepare("INSERT INTO book (title, subtitle, publication_year, id_publisher, actual_page, finished) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param('ssiiib', $data['title'], $data['subtitle'], $data['publication_year'], $data['id_publisher'], $data['actual_page'], $finished);

    if ($sql->execute() === TRUE) {
        $id_book = $conn->insert_id;
        $sql = $conn->prepare("INSERT INTO book_author (id_book, id_author) VALUES (?, ?)");
        foreach($data['id_author'] as $id_author) { 
            $sql->bind_param('ii', $id_book, $id_author);
            $sql->execute();
        }
        $message = $data['title']." salvo!";
        header("Location: /PIE3/pages/book/books.php?success=".urlencode($message));
        exit();
    } else {
        $message = "O seguinte erro ocorreu ao tentar incluiro livro ".$data['title'].":<br>".$sql->error;
        header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
    exit();
}