<?php
function executeQuery($query, $obj, $objUrl, $successText = "A requisição foi processada com sucesso.", $errorText = "O seguinte erro ocorreu ao tentar processar a requisição ") {
    try {
        $query->execute();
        $message = $successText;
        header("Location: /PIE3/pages/".$objUrl."/".$objUrl."s.php?success=".urlencode($message));
        exit();
    } catch (Exception $error) {
        $message = $errorText.$obj['name'].": ".$error->getMessage();
        header("Location: /PIE3/pages/".$objUrl."/".$objUrl."s.php?error=".urlencode($message));
        exit();
    }
}