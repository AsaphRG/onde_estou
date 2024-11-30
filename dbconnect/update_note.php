<?php
session_start();
require_once "../config.php";
$data = formTreatment($_POST);

if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    $note_query = $conn->prepare("SELECT * FROM note WHERE id_note = ?");
    $note_query->bind_param('i', $data['id_note']);
    $note_query->execute();
    $result_note_query = $note_query->get_result();
    $note = $result_note_query->fetch_assoc();

    if ($note) {
        $update_note = [
            'init_page' => $data['init_page'],
            'end_page' => $data['end_page'],
            'note' => $data['note']
        ];

        $update_note_query = [];
        $bind_param_types = "";
        $fields_to_change = [];

        if ($update_note['init_page'] != $note['init_page']) {
            $update_note_query[] = "init_page = ?";
            $bind_param_types .= "i";
            $fields_to_change[] = $update_note['init_page'];
        }
        if ($update_note['end_page'] != $note['end_page']) {
            $update_note_query[] = "end_page = ?";
            $bind_param_types .= "i";
            $fields_to_change[] = $update_note['end_page'];
        }
        if ($update_note['note'] != $note['note']) {
            $update_note_query[] = "note = ?";
            $bind_param_types .= "s";
            $fields_to_change[] = $update_note['note'];
        }
        
        if ($update_note_query && $fields_to_change) {
            $update_query_str = "UPDATE note SET ".implode(", ", $update_note_query)." WHERE id_note = ?";
            $update_query = $conn->prepare($update_query_str);
            $bind_param_types .= 'i';
            $fields_to_change[] = $note['id_note'];
            $update_query->bind_param($bind_param_types, ...$fields_to_change);
            try {
                $update_query->execute();
                $message = "Nota foi atualizada!";
                header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&success=".urlencode($message));
                exit();
            } catch (Exception $error) {
                $message = "O seguinte erro ocorreu ao tentar alterar a nota:<br>".$error->getMessage();
                header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&error=".urlencode($message));
                exit();
            }
        } else {
            $message = "Não há campos a serem atualizados.";
            header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&info=".urlencode($message));
            exit();
        }
    } else {
        $message = "Nota não encontrada.";
        header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&error=".urlencode($message));
        exit();
    }
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/book/book.php?id=".$data['id_book']."&error=".urlencode($message));
    exit();
}