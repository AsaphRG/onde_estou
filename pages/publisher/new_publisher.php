<?php require '../../partials/init.php' ?>

<form action="/PIE3/dbconnect/create_publisher.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="form-group">
        <div class="form-field">
            <label class="form-label" for="name">Nome:</label>
            <input class="form-control" type="text" name="name" id="name" autofocus required>
        </div>
        <input class="btn btn-primary" type="submit" value="Enviar">
    </div>
</form>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>