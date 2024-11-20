<?php
require '../../partials/init.php';
$new_path = '/pages/publisher/new_publisher.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$id_publisher = $_GET['id'];
$id_publisher = htmlspecialchars($id_publisher);
if ($id_publisher) {
    $query = $conn->prepare("SELECT * FROM publisher WHERE id_publisher = ?");
    $query->bind_param('i', $id_publisher);
    $query->execute();
    $result = $query->get_result();
    $publisher = $result->fetch_assoc();
}
?>

<section>
    <form action="/PIE3/dbconnect/update_publisher.php" method="post">
        <input type="hidden" name="id_publisher" value="<?= $publisher['id_publisher'] ?>">
        <div class="form-group">
            <label for="name" class="form-field">Autor:</label>
            <input type="text" name="name" id="name" value="<?= $publisher['name'] ?>">
        </div>
        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>