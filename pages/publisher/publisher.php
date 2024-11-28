<?php
require '../../partials/init.php';
$new_path = '/pages/publisher/new_publisher.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$data = get_messages($_GET);
$id_publisher = $data['id'];
if ($id_publisher) {
    $query = $conn->prepare("SELECT * FROM publisher WHERE id_publisher = ?");
    $query->bind_param('i', $id_publisher);
    $query->execute();
    $result = $query->get_result();
    $publisher = $result->fetch_assoc();
}
if (!$publisher) {
    $message = "Editora id $id_publisher não encontrado.";
    header("Location: /PIE3/pages/publisher/publishers.php?error=$message");
    exit();
}
?>

<section>
    <form action="/PIE3/dbconnect/update_publisher.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="id_publisher" value="<?= $publisher['id_publisher'] ?>">
        <div class="form-group">
            <label class="form-label" for="name" class="form-field">Editora:</label>
            <input class="form-control" type="text" name="name" id="name" value="<?= $publisher['name'] ?>" autofocus required>
        </div>
        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>