<?php

function formTreatment($form): array {
    $data = [];
    foreach ($form as $key => $value) {
        is_string($value) ? $data[$key] = htmlspecialchars($value) : $data[$key] = $value;
    }
    return $data;
}
