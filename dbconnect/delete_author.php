<?php
session_start();
require "../config.php";

$data = formTreatment($_POST);
$id = $data['id_author'];

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
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

            try {
                $query->execute();
                $message = $author['name']." excluído(a) com sucesso.";
                header("Location: /PIE3/pages/author/authors.php?success=".urlencode($message));
                exit();
            } catch (Exception $error) {
                $message = "O seguinte erro ocorreu ao tentar excluir o autor ".$author['name'].": ".$error->getMessage();
                header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
                exit();
            }
        } else {
            header("Location: /PIE3/pages/author/authors.php?error=Registro não encontrado para a chave enviada.");
            exit();
        }
        
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
    exit();
}