<?php
session_start();
require_once '../config.php';

$data = formTreatment($_POST);
if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    $finished = isset($data['finished']);

    $sql = $conn->prepare("INSERT INTO book (title, subtitle, publication_year, id_publisher, actual_page, finished) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param('ssiiib', $data['title'], $data['subtitle'], $data['publication_year'], $data['id_publisher'], $data['actual_page'], $finished);

    try {
        $sql->execute();
        $id_book = $conn->insert_id;
        $sql = $conn->prepare("INSERT INTO book_author (id_book, id_author) VALUES (?, ?)");
        foreach($data['id_author'] as $id_author) { 
            $sql->bind_param('ii', $id_book, $id_author);
            try {
                $sql->execute();
            } catch (Exception $error) {
                $message = "O seguinte erro ocorreu ao tentar incluir o autor $id_author: ".$error->getMessage();
                header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
                exit();
            }
        }
        $message = $data['title']." salvo!";
        header("Location: /PIE3/pages/book/books.php?success=".urlencode($message));
        exit();
    } catch (Exception $error) {
        $message = "O seguinte erro ocorreu ao tentar incluir o livro ".$data['title'].": ".$error->getMessage();
        header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
    exit();
}