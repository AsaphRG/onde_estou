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
    if (!$book) {
        $message = "Livro id $id_book não encontrado.";
        header("Location: /PIE3/pages/book/books.php?error=$message");
        exit();
    }

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

    // Traz as notas do livro
    $query_notes = $conn->prepare("SELECT * FROM note WHERE id_book = ?");
    $query_notes->bind_param('i', $id_book);
    $query_notes->execute();
    $result = $query_notes->get_result();
    $notes = $result->fetch_all(MYSQLI_ASSOC);
}

$all_authors = $conn->query("SELECT * FROM author");
$all_authors = $all_authors->fetch_all();

$all_publishers = $conn->query("SELECT * FROM publisher");
$all_publishers = $all_publishers->fetch_all();
?>

<section>
    <div class="book-header">
        <form action="/PIE3/dbconnect/update_book.php" method="POST">
            <div class="form-group">
                <input type="hidden" name="id_book" value="<?= $book['id_book'] ?>">
            
                <div class="form-field">
                    <label class="form-label" for="title">Título:</label>
                    <input class="form-control" type="text" name="title" id="title" value="<?= $book['title'] ?>" autofocus required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="subtitle">Subtítulo:</label>
                    <input class="form-control" type="text" name="subtitle" id="subtitle" value="<?= $book['subtitle'] ?>">
                </div>

                <div class="form-field">
                    <label class="form-label" for="authors">Autores:</label>
                    <select class="form-select" name="authors[]" id="authors" multiple required>
                        <?php foreach($all_authors as $s_author) : ?>
                            <option class="form-" value="<?= $s_author[0] ?>" <?= in_array($s_author[0], $id_authors) ? 'selected' : '' ?>><?= $s_author[1] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-label" for="publication-year">Ano de publicação:</label>
                    <input class="form-control" type="number" name="publication_year" id="publication-year" value="<?= $book['publication_year'] ?>" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="publisher">Editora:</label>
                    <select class="form-select" name="id_publisher" id="id-publisher">
                        <?php foreach($all_publishers as $s_publisher) : ?>
                            <option class="form-" value="<?= $s_publisher[0] ?>" <?= $s_publisher[0] == $book['id_publisher'] ? 'selected' : '' ?>><?= $s_publisher[1] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-label" for="actual-page">Página atual:</label>
                    <input class="form-control" type="number" name="actual_page" id="actual-page" value="<?= $book['actual_page'] ?>">
                </div>

                <div class="form-field">
                    <label class="form-check-label" for="finished">Finalizado:</label>
                    <input class="form-check-input" type="checkbox" name="finished" id="finished" <?= $book['finished'] ? 'checked' : ''?>>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
    <div class="book-notes">
        <div class="new-note">
            <form action="/PIE3/dbconnect/create_note.php" method="POST">
                <div class="form-group">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="id_book" value="<?= $book['id_book'] ?>">
                    <div class="note note-number">Nota #<?= sizeof($notes) + 1 ?></div>
                    <div class="note note-pages">
                        Sobre a(s) página(s): <input class="form-control note-pages" type="number" name="init_page" id="init-page"> a <input class="form-control note-pages" type="number" name="end_page" id="end_page">
                    </div>
                    <div class="form-field">
                        <label class="form-label" for="note">Nota:</label>
                        <textarea class="form-control" name="note" id="note" required></textarea>
                    </div>
                    <div class="note-buttons">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check"></i></button>
                        <button class="btn btn-primary" type="reset"><i class="fa-solid fa-eraser"></i></i></button>
                    </div>
                </div>
            </form>
        </div>
        <?php foreach ($notes as $index => $note) : ?>
            <form action="/PIE3/dbconnect/update_note.php?id_note=<?= $note['id_note'] ?>" method="post">
                <div class="form-group">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="id_book" value="<?= $book['id_book'] ?>">
                    <input type="hidden" name="id_note" value="<?= $note['id_note'] ?>">
                    <div class="note_number">Nota #<?= $index + 1 ?></div>
                    <div class="note note-pages">
                        Sobre a(s) página(s): <input class="form-control note-pages" type="number" name="init_page" id="init-page" value="<?= $note['init_page'] ?>"> a <input class="form-control note-pages" type="number" name="end_page" id="end_page" value="<?= $note['end_page'] ?>">
                    </div>
                    <div class="form-field">
                        <label class="form-label" for="note">Nota:</label>
                        <textarea class="form-control" name="note" id="note" required><?= $note['note'] ?></textarea>
                    </div>
                    <div class="note-buttons">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check"></i></button>
                        <button class="btn btn-primary" type="reset"><i class="fa-solid fa-eraser"></i></i></button>
                    </div>
                </div>
            </form>
            <form action="/PIE3/dbconnect/delete_note.php" method="post"><input type="hidden" name="csrf_token" value="<?= $csrf_token ?>"><input type="hidden" name="id_book" value="<?= $book['id_book'] ?>"><input type="hidden" name="id_note" value="<?= $note['id_note'] ?>"><button type="submit" class="btn btn-danger"><i class='fa-solid fa-xmark'></i></button></form>
        <?php endforeach ?>
    </div>
</section>
<?php require ABSOLUTE_PATH.'/partials/end.php' ?>