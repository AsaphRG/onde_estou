<?php
session_start();
require "../config.php";
$data = formTreatment($_POST);
$id = $data['id'];

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    if (!empty($id)) {
        $select_query = $conn->prepare("SELECT * FROM book WHERE id_book = ?");
        $select_query->bind_param('i', $id);
        try {
            $select_query->execute();
            $result = $select_query->get_result();
            $book = $result->fetch_assoc();
            $select_query->close();
        } catch (Exception $error) {
            $message = "O seguinte erro ocorreu ao tentar consultar o livro $id: ".$error->getMessage();
            header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
            exit();
        }

        if ($book) {
            $primary_query = "DELETE FROM book WHERE id_book=$id";
            $secondary_query = "DELETE FROM book_author WHERE id_book=$id";

            try {
                $conn->query($secondary_query);
                $conn->query($primary_query);
                $message = $book['title']." excluído com sucesso.";
                header("Location: /PIE3/pages/book/books.php?success=".urlencode($message));
                exit();
            } catch (Exception $error) {
                $message = "O seguinte erro ocorreu ao tentar excluir o livro ".$book['title'].": ".$error->getMessage();
                header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
                exit();
            }
        } else {
            header("Location: /PIE3/pages/book/books.php?error=Registro não encontrado para a chave enviada.");
            exit();
        }
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
    exit();
}