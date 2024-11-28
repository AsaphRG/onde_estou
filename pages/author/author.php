<?php
require '../../partials/init.php';
$new_path = '/pages/author/new_author.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$data = get_messages($_GET);
$id_author = $data['id'];
if ($id_author) {
    $query = $conn->prepare("SELECT * FROM author WHERE id_author = ?");
    $query->bind_param('i', $id_author);
    $query->execute();
    $result = $query->get_result();
    $author = $result->fetch_assoc();
}
if (!$author) {
    $message = "Autor id $id_author não encontrado.";
    header("Location: /PIE3/pages/author/authors.php?error=$message");
    exit();
}
?>

<section>
    <form action="/PIE3/dbconnect/update_author.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="id_author" value="<?= $author['id_author'] ?>">
        <div class="form-group">
            <div class="form-field">
                <label class="form-label" for="name">Autor:</label>
                <input class="form-control" type="text" name="name" id="name" value="<?= $author['name'] ?>" autofocus required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>