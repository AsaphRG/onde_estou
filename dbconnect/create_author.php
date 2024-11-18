<?php
require_once '../config.php';
$data = formTreatment($_POST);

$name = $data['name'];

$sql = $conn->prepare("INSERT INTO author (name) VALUES (?)");
$sql->bind_param('s', $data['name']);

if ($sql->execute() === TRUE) {
    // echo "<center><h1>Registro Inserido com Sucesso</h1>";
    // echo "<a href='/PIE3/pages/author/authors.php'><input type='button' value='Voltar'></a></center>";
    $id_author = $conn->insert_id;
    header("Location: /PIE3/pages/author/authors.php?success=$id_author");
    exit();
} else {
    // echo "<h3>OCORREU UM ERRO: </h3>: " . $sql . "<br>" . $conn->error;
    header("Location: /PIE3/pages/author/authors.php?error=$conn->error");
    exit();
}