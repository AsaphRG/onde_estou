<?php
require_once '../../partials/init.php';
$publishers = mysqli_query($conn, 'SELECT * FROM publisher');
$authors = mysqli_query($conn, 'SELECT * FROM author');
?>
<form action="/PIE3/dbconnect/create_book.php" method="POST">
    <div class="form-group">
        <div class="form-field">
            <label for="title">Título:</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div class="form-field">
            <label for="subtitle">Subtítulo:</label>
            <input type="text" name="subtitle" id="subtitle">
        </div>
        <div class="form-field">
            <label for="publication_year">Ano de publicação:</label>
            <input type="text" name="publication_year" id="publication_year" required>
        </div>
        <div class="form-field">
            <label for="id_author">Autor(es):</label>
            <select name="id_author[]" id="id_author" multiple>
                <option value="" selected disabled>Selecione</option>
                <?php foreach ($authors as $author) {
                    echo "<option value='".$author['id_author']."'>".$author['name']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-field">
            <label for="id_publisher">Editora:</label>
            <select name="id_publisher" id="id_publisher">
                <option value="" selected disabled>Selecione</option>
                <?php foreach ($publishers as $publisher) {
                    echo "<option value='".$publisher['id_publisher']."'>".$publisher['name']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-field">
            <label for="actual_page">Página atual:</label>
            <input type="number" name="actual_page" id="actual_page">
        </div>
        <div class="form-field">
            <label for="finished">Terminou?</label>
            <input type="checkbox" name="finished" id="finished">
        </div>
        <input type="submit" value="Enviar">
    </div>
</form>
<?php require ABSOLUTE_PATH.'/partials/end.php' ?>