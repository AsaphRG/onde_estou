<?php

// O número da página da anotação deve atualizar a página do livro se a página sobre a qual a anotação foi feita for maior do que a página atual do livro.
session_start();
require_once '../config.php';
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    if (isset($data['id_book'])) {

        $query_book = $conn->prepare("SELECT * FROM book WHERE id_book = ?");
        $query_book->bind_param('i', $data['id_book']);
        $query_book->execute();
        $result_query_book = $query_book->get_result();
        $book = $result_query_book->fetch_assoc();

        $create_note = $conn->prepare("INSERT INTO note (id_book, init_page, end_page, note) VALUES (?, ?, ?, ?)");
        $create_note->bind_param('iiis', $data['id_book'], $data['init_page'], $data['end_page'], $data['note']);
        try {
            $create_note->execute();
            $message = "Nota salva!";
            header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&success=".urlencode($message));
            exit();
        } catch (Exception $error) {
            $message = "O seguinte erro ocorreu ao tentar incluir a nota no livro ".$book['title'].": ".$error->getMessage();
            header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&error=".urlencode($message));
            exit();
        }
    } else {
        $message = "Livro id ".$data['id_book']." não encontrado.";
        header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&error=".urlencode($message));
    exit();
}