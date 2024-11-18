<?php
require_once '../config.php';

$data = formTreatment($_POST);
$finished = isset($data['finished']);

$sql = $conn->prepare("INSERT INTO book (title, subtitle, publication_year, id_publisher, actual_page, finished) VALUES (?, ?, ?, ?, ?, ?)");
$sql->bind_param('ssiiib', $data['name'], $data['subtitle'], $data['publication_year'], $data['id_publisher'], $data['actual_page'], $finished);

if ($sql->execute() === TRUE) {
    $id_book = $conn->insert_id;
    $sql = $conn->prepare("INSERT INTO book_author (id_book, id_author) VALUES (?, ?)");
    foreach($data['id_author'] as $id_author) { 
        $sql->bind_param('ii', $id_book, $id_author);
        $sql->execute();
    }
    // echo "<center><h1>Registro Inserido com Sucesso</h1>";
    // echo "<a href='/PIE3/pages/book/books.php'><input type='button' value='Voltar'></a></center>";
    header("Location: /PIE3/pages/book/books.php?success=$id_book");
    exit();
} else {
    // echo "<h3>OCORREU UM ERRO: </h3>: " . $sql . "<br>" . $conn->error;
    header("Location: /PIE3/pages/book/books.php?error=$conn->error");
    exit();
}