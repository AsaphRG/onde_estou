<?php
require "../config.php";

$id = $_POST['id'];

if (!empty($id)) {
    $select_query = $conn->prepare("SELECT * FROM book WHERE id = ?");
    $select_query->bind_param('i', $id);
    $select_query->execute();
    $result = $select_query->get_result();
    $book = $result->fetch_assoc();
    $select_query->close();

    if ($book) {
        $primary_query = "DELETE FROM book WHERE id_book=$id";
        $secondary_query = "DELETE FROM book_author WHERE id_book=$id";

        if (($conn->query($secondary_query) && $conn->query($primary_query)) === TRUE) {
            $message = $book['title']." excluído com sucesso.";
            header("Location: /PIE3/pages/book/books.php?success=".urlencode($message));
            exit();
        } else {
            $message = "O seguinte erro ocorreu ao tentar excluir o livro ".$book['title'].":<br>".$query->error;
            header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
            exit();
        }
    } else {
        header("Location: /PIE3/pages/book/books.php?error=Registro não encontrado para a chave enviada.");
        exit();
    }
}