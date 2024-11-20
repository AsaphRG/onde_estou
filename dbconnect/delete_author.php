<?php
require "../config.php";

$id = $_POST['id'];

if (!empty($id)) {
    $request_item_to_delete = $conn->prepare("SELECT * FROM author WHERE id_author = ?");
    $request_item_to_delete->bind_param('i', $id);
    $request_item_to_delete->execute();
    $result = $request_item_to_delete->get_result();
    $request_item_to_delete->close();
    $author = $result->fetch_assoc();
    if($author) {
        $query = $conn->prepare("DELETE FROM author WHERE id_author = ?");
        $query->bind_param('i', $id);

        if ($query->execute() === TRUE) {
            $message = $author['name']." excluído(a) com sucesso.";
            header("Location: /PIE3/pages/author/authors.php?success=".urlencode($message));
            exit();
        } else {
            $message = "O seguinte erro ocorreu ao tentar excluir o autor ".$author['name'].":<br>".$query->error;
            header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
            exit();
        }
    } else {
        header("Location: /PIE3/pages/author/authors.php?error=Registro não encontrado para a chave enviada.");
        exit();
    }
    
}
