<?php

function formTreatment($form): array {
    $data = [];
    foreach ($form as $key => $value) {
        if (is_string($value)) {
            $data[$key] = htmlspecialchars($value);
        } else if (is_array($value)) {
            $data[$key] = formTreatment($value);
        } else {
            $data[$key] = $value;
        }
    }
    return $data;
}

function get_messages($data) {
    $aux = [];
    foreach ($data as $key => $value) {
        $aux[$key] = htmlspecialchars($value);
    }
    return $aux;
}