<?php
require '../../partials/init.php';
$new_path = '/pages/book/new_book.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$id_book = $_GET['id'];
$id_book = htmlspecialchars($id_book);
if ($id_book) {
    $query = $conn->prepare('SELECT * FROM book WHERE id_book = ?');
    $query->bind_param('i', $id_book);
    $query->execute();
    $result = $query->get_result();
    $book = $result->fetch_assoc();

    // Traz os autores do livro de acordo com cada livro
    $query_id_authors = $conn->prepare('SELECT * FROM book_author WHERE id_book = ?');
    $query_id_authors->bind_param('i', $id_book);
    $query_id_authors->execute();
    $result_id_authors = $query_id_authors->get_result();
    $id_authors = $result_id_authors->fetch_all();

    // Extrai os ids do array id_authors e inclui na variável id_authors como um array de ids
    $aux = [];
    foreach ($id_authors as $id_author) {
        $aux[] = $id_author[1];
    }
    $id_authors = $aux;
    
    // Consulta o banco de dados trazendo o resultado da consulta como um array de author
    $authors = [];
    foreach ($id_authors as $id_author) {
        $query_authors = $conn->prepare('SELECT * FROM author WHERE id_author = ?');
        $query_authors->bind_param('i', $id_author);
        $query_authors->execute();
        $result_authors = $query_authors->get_result();
        $author = $result_authors->fetch_assoc();
        $authors[] = $author;
    }

    // Traz a editora do livro
    $query_publisher = $conn->prepare('SELECT * FROM publisher WHERE id_publisher = ?');
    $query_publisher->bind_param('i', $book['id_publisher']);
    $query_publisher->execute();
    $result_publisher = $query_publisher->get_result();
    $publisher = $result_publisher->fetch_assoc();
}

$all_authors = $conn->query("SELECT * FROM author");
$all_authors = $all_authors->fetch_all();

$all_publishers = $conn->query("SELECT * FROM publisher");
$all_publishers = $all_publishers->fetch_all();
?>

<section>
    <div class="book-header">
        <form action="/PIE3/dbconnect/update_book.php" method="POST">

            <input type="hidden" name="id_book" value="<?= $book['id_book'] ?>">
            
            <div class="form-group">
                <label class="form-field" for="title">Título:</label>
                <input class="form-field" type="text" name="title" id="title" value="<?= $book['title'] ?>">
            </div>

            <div class="form-group">
                <label class="form-field" for="subtitle">Subtítulo:</label>
                <input class="form-field" type="text" name="subtitle" id="subtitle" value="<?= $book['subtitle'] ?>">
            </div>

            <div class="form-group">
                <label class="form-field" for="authors">Autores:</label>
                <select name="authors[]" id="authors" multiple>
                    <?php foreach($all_authors as $s_author) : ?>
                        <option class="form-field" value="<?= $s_author[0] ?>" <?= in_array($s_author[0], $id_authors) ? 'selected' : '' ?>><?= $s_author[1] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-field" for="publication-year">Ano de publicação:</label>
                <input class="form-field" type="number" name="publication_year" id="publication-year" value="<?= $book['publication_year'] ?>">
            </div>

            <div class="form-group">
                <label class="form-field" for="publisher">Editora:</label>
                <select name="id_publisher" id="id-publisher">
                    <?php foreach($all_publishers as $s_publisher) : ?>
                        <option class="form-field" value="<?= $s_publisher[0] ?>" <?= $s_publisher[0] == $book['id_publisher'] ? 'selected' : '' ?>><?= $s_publisher[1] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-field" for="actual-page">Página atual:</label>
                <input class="form-field" type="number" name="actual_page" id="actual-page" value="<?= $book['actual_page'] ?>">
            </div>

            <div class="form-group">
                <label class="form-field" for="finished">Finalizado:</label>
                <input class="form-field" type="checkbox" name="finished" id="finished" <?= $book['finished'] ? 'checked' : ''?>>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</section>
<?php require ABSOLUTE_PATH.'/partials/end.php' ?>
