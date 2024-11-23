<?php require '../../partials/init.php' ?>

<form action="/PIE3/dbconnect/create_author.php" method="POST">
    <div class="form-group">
        <div class="form-field">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" autofocus required>
        </div>
        <input type="submit" class="btn btn-primary" value="Enviar">
    </div>
</form>
<?php require ABSOLUTE_PATH.'/partials/end.php' ?>