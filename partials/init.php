<?php
require_once 'C:xampp/htdocs/PIE3/config.php';

require ABSOLUTE_PATH.'/dbconnect/connect.php';

$response = get_messages($_GET);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <?php require ABSOLUTE_PATH.'/partials/head.php' ?>
    <body>
        <header>
            <?php require ABSOLUTE_PATH.'/partials/header.php' ?>
        </header>
        <main>
            <?php if (isset($response['success'])) : ?>
                <div class="alert alert-success"><?= urldecode($response['success']) ?></div>
            <?php endif; ?>
            <?php if (isset($response['error'])) : ?>
                <div class="alert alert-danger"><?= urldecode($response['error']) ?></div>
            <?php endif; ?>
            <?php if (isset($response['info'])) : ?>
                <div class="alert alert-info"><?= urldecode($response['info']) ?></div>
            <?php endif; ?>