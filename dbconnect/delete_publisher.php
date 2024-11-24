<?php
session_start();
require "../config.php";

$data = formTreatment($_POST);

$id = $data['id'];

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    if (!empty($id)) {
        $request_item_to_delete = $conn->prepare("SELECT * FROM publisher WHERE id_publisher = ?");
        $request_item_to_delete->bind_param('i', $id);
        $request_item_to_delete->execute();
        $result = $request_item_to_delete->get_result();
        $request_item_to_delete->close();
        $publisher = $result->fetch_assoc();
        if($publisher) {
            $query = $conn->prepare("DELETE FROM publisher WHERE id_publisher = ?");
            $query->bind_param('i', $id);

            if ($query->execute() === TRUE) {
                $message = $publisher['name']." excluída com sucesso.";
                header("Location: /PIE3/pages/publisher/publishers.php?success=".urlencode($message));
                exit();
            } else {
                $message = "O seguinte erro ocorreu ao tentar excluir a editora ".$publisher['name'].":<br>".$query->error;
                header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
                exit();
            }
        } else {
            header("Location: /PIE3/pages/publisher/publishers.php?error=Registro não encontrado para a chave enviada.");
            exit();
        }
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/publisher/publishers.php?error=".urlencode($message));
    exit();
}