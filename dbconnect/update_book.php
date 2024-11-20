<?php
require_once '../config.php';
$data = formTreatment($_POST);

$new_book = [
    "id_book" => $data['id_book'],
    "title" => $data['title'],
    "subtitle" => $data['subtitle'],
    "publication_year" => $data['publication_year'],
    "id_publisher" => $data['id_publisher'],
    "actual_page" => $data['actual_page'],
    "finished" => isset($data['finished']) ? TRUE : FALSE,
];

$query_id_authors = $conn->prepare("SELECT * FROM book_author WHERE id_book = ?");
$query_id_authors->bind_param('i', $data['id_book']);
$query_id_authors->execute();
$result_id_authors = $query_id_authors->get_result();
$book_authors = $result_id_authors->fetch_all();
$id_authors = [];

foreach ($book_authors as $book_author) {
    $id_authors[] = $book_author[1];
}

$query_book = $conn->prepare("SELECT * FROM book WHERE id_book = ?");
$query_book->bind_param('i', $data['id_book']);
$query_book->execute();
$result_query_book = $query_book->get_result();
$book = $result_query_book->fetch_assoc();

$update_book_query = [];
$bind_param_types = '';
$fields_to_change = [];

if ($new_book['title'] != $book['title']) {
    $update_book_query[] = "title = ?";
    $bind_param_types .= "s";
    $fields_to_change[] = $new_book['title'];
}
if ($new_book['subtitle'] != $book['subtitle']) {
    $update_book_query[] = "subtitle = ?";
    $bind_param_types .= "s";
    $fields_to_change[] = $new_book['subtitle'];
}
if ($new_book['publication_year'] != $book['publication_year']) {
    $update_book_query[] = "publication_year = ?";
    $bind_param_types .= "i";
    $fields_to_change[] = $new_book['publication_year'];
}
if ($new_book["id_publisher"] != $book["id_publisher"]) {
    $update_book_query[] = "id_publisher = ?";
    $bind_param_types .= "i";
    $fields_to_change[] = $new_book['id_publisher'];
}
if ($new_book["actual_page"] != $book["actual_page"]) {
    $update_book_query[] = "actual_page = ?";
    $bind_param_types .= "i";
    $fields_to_change[] = $new_book['actual_page'];
}
if ($new_book["finished"] != $book["finished"]) {
    $update_book_query[] = "finished = ?";
    $bind_param_types .= "i";
    $fields_to_change[] = (int)$new_book['finished'];
}

$authors_to_insert = array_diff($data['authors'], $id_authors);
foreach ($authors_to_insert as $id_author) {
    $insert_book_author = $conn->prepare("INSERT INTO book_author (id_book, id_author) VALUES (?, ?)");
    $insert_book_author->bind_param('ii', $new_book['id_book'], $id_author);
    if (!$insert_book_author->execute()) {
        $message = "Erro ao inserir o autor $id_author no livro ".$new_book['title'].": ".$insert_book_author->error;
        header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
        exit();
    }
}

$authors_to_delete = array_diff($id_authors, $data['authors']);
foreach ($authors_to_delete as $id_author) {
    $delete_book_author = $conn->prepare("DELETE FROM book_author WHERE id_book = ? AND id_author = ?");
    $delete_book_author->bind_param('ii', $new_book['id_book'], $id_author);
    if (!$delete_book_author->execute()) {
        $message = "Erro ao remover o autor $id_author do livro ".$new_book['title'].": ".$delete_book_author->error;
        header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
        exit();
    }
}

if ($update_book_query && $fields_to_change) {
    $update_query_str = "UPDATE book SET ".implode(", ", $update_book_query)." WHERE id_book = ?";
    $update_query = $conn->prepare($update_query_str);
    $bind_param_types .= 'i';
    $fields_to_change[] = $book['id_book'];
    $update_query->bind_param($bind_param_types, ...$fields_to_change);
    if ($update_query->execute()) {
        $message = $new_book['title']." foi atualizado!";
        header("Location: /PIE3/pages/book/books.php?success=".urlencode($message));
        exit();
    } else {
        $message = "O seguinte erro ocorreu ao tentar alterar o livro ".$new_book['name'].":<br>".$update_query->error;
        header("Location: /PIE3/pages/book/books.php?error=".urlencode($message));
        exit();
    }
} else if ($authors_to_insert || $authors_to_delete) {
    $message = $new_book['title']." foi atualizado!";
    header("Location: /PIE3/pages/book/books.php?success=".urlencode($message));
    exit();
} else {
    $message = "Não há campos a serem atualizados.";
    header("Location: /PIE3/pages/book/books.php?info=".urlencode($message));
    exit();
}